import pandas as pd
import os
import re   
import numpy as np
import sys
import json
import pymysql
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import linear_kernel
import warnings

warnings.filterwarnings("ignore")
product_ids = sys.argv[1].split(',')
product_ids = [int(i) for i in product_ids if i.isdigit()]

try:
    conn = pymysql.connect(
        host="127.0.0.1",
        user="appuser",
        password="appuser@password",
        database="online_portal",
        port=3306,
        connect_timeout=5
    )
except Exception as err:
    print(json.dumps({"error": str(err)}))
    sys.exit()

query = """
SELECT t1.p_id, t1.product_name AS name, t2.product_category_name AS category,t1.product_price AS price,t1.product_description AS description,
t1.product_image AS image, t1.product_weight_qty AS weight, t1.product_weight_unit AS unit FROM product_detail t1
INNER JOIN product_category t2 ON t1.pc_id = t2.pc_id
"""
df = pd.read_sql(query, conn)
conn.close()

df["name"] = df["name"].fillna("")
df["category"] = df["category"].fillna("")
df["description"] = df["description"].fillna("")


df["text"] = (
    (df["name"].astype(str) + " ") * 3 +
    (df["category"].astype(str) + " ") * 5 +
    (df["description"].astype(str) + " ") * 1 
)


tf_idf = TfidfVectorizer(
    stop_words='english',
    ngram_range=(1,2),
    min_df=1
)

tf_idf_matrix = tf_idf.fit_transform(df["text"])


idx_list = []
for p_id in product_ids:
    i = df.index[df["p_id"] == p_id].tolist()
    if len(i) > 0:
        idx_list.append(i[0])

if not idx_list:
    print("[]")
    exit()


vector = tf_idf_matrix[idx_list]
avg_vector = vector.mean(axis=0)
avg_vector = np.asarray(avg_vector)


similarity = linear_kernel(avg_vector, tf_idf_matrix).flatten()


input_price = df.iloc[idx_list]["price"].mean()


final_scores = []

for i, sim in enumerate(similarity):
    if i in idx_list:
        continue  # skip selected products
    
    product_price = df.iloc[i]["price"]
    
    
    price_sim = 1 / (1 + abs(product_price - input_price))
    
    
    final_score = 0.7 * sim + 0.3 * price_sim
    
    final_scores.append((i, final_score))


final_scores = sorted(final_scores, key=lambda x: x[1], reverse=True)


top_indices = [i[0] for i in final_scores[:6]]


recommendations = df[['p_id', 'name', 'image', 'price','weight','unit']].iloc[top_indices]

if recommendations.empty:
    print(json.dumps({"error": "No recommended products found"}))
    sys.exit()


recommendations_list = recommendations.to_dict(orient='records')
print(json.dumps(recommendations_list))