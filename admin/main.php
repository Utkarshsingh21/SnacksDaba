<?php
$conn = new mysqli("localhost", "root", "", "online_portal");
if ($conn->connect_error) {
    die("ERROR:Connection Failed -" . $conn->connect_error);
}
if (isset($_GET["flag"])) {
    $flag = $_GET["flag"];
    switch ($flag) {
        case 1:                         #adding product category
            $p_category = addslashes($_POST["p_category"]);
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d h:i:s');
            $sql = "insert into product_category(product_category_name,product_adddate) values('$p_category','$datetime')";
            $result = mysqli_query($conn, $sql);
            if ($result == true) {
                header("location:add_product_category.php?msg=1");
            } else {
                header("location:add_product_category.php?err=1");
            }
            break;

        case 2:                     # adding product and product details
            $p_name    = addslashes($_POST["p_name"]);
            $p_descrip = addslashes($_POST["p_descrip"]);
            $p_price   = $_POST["p_price"];
            $p_weight  = $_POST["p_weight"];
            $p_unit    = $_POST["p_unit"];
            $p_category = $_POST["p_category"];
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d h:i:s');
            if (isset($_FILES["p_image"]) && $_FILES["p_image"]["error"] === UPLOAD_ERR_OK) {
                $size = $_FILES["p_image"]["size"];
                $name = $_FILES["p_image"]["name"];
                $maxsize  = 5 * 1024 * 1024;
                $tmp_name = $_FILES["p_image"]["tmp_name"];
                $fileext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $uploadedFileName = "p_image" . random_int(10000, 99999) . "." . $fileext;
                $destination = $_SERVER['DOCUMENT_ROOT'] . "/online_portal/product_image/" . $uploadedFileName;
                $relativePath = "/online_portal/product_image/" . $uploadedFileName;
                $allowed = ['jpg', 'png', 'jpeg'];
                if (!in_array($fileext, $allowed)) {
                    header("location:add_product.php?err=1");
                } elseif ($size > $maxsize) {
                    header("location:add_product.php?err=2");
                } else {
                    if (move_uploaded_file($tmp_name, $destination)) {
                        $sql = "insert into product_detail(product_name,product_description,product_price,product_weight_qty,product_weight_unit,product_add_dtime,product_image,pc_id) values ('$p_name','$p_descrip','$p_price','$p_weight','$p_unit','$datetime','$relativePath','$p_category')";
                        mysqli_query($conn, $sql);
                        header("location:add_product.php?msg=1");
                    } else {
                        header("location:add_product.php?err=3");
                    }
                }
            } else {
                $sql = "insert into product_detail(product_name,product_description,product_price,product_weight_qty,product_weight_unit,product_add_dtime,product_image,pc_id) values ('$p_name','$p_descrip','$p_price','$p_weight','$p_unit','$datetime',NULL,'$p_category')";
                mysqli_query($conn, $sql);
                header("location:add_product.php?msg=1");
            }
            break;

        case 3:
            # Edit Product details
            $p_id      = $_POST["p_id"];
            $p_name    = addslashes($_POST["p_name"]);
            $p_descrip = addslashes($_POST["p_descrip"]);
            $p_price   = $_POST["p_price"];
            $p_weight  = $_POST["p_weight"];
            $p_unit    = $_POST["p_unit"];
            $p_category = $_POST["p_category"];
            date_default_timezone_set('Asia/Kolkata');
            $datetime = date('Y-m-d h:i:s');
            if (isset($_FILES["p_image"]) && $_FILES["p_image"]["error"] === UPLOAD_ERR_OK) {
                $size = $_FILES["p_image"]["size"];
                $name = $_FILES["p_image"]["name"];
                $maxsize  = 5 * 1024 * 1024;
                $tmp_name = $_FILES["p_image"]["tmp_name"];
                $fileext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $uploadedFileName = "p_image" . random_int(10000, 99999) . "." . $fileext;
                $destination = $_SERVER['DOCUMENT_ROOT'] . "/online_portal/product_image/" . $uploadedFileName;
                $relativePath = "/online_portal/product_image/" . $uploadedFileName;
                $allowed = ['jpg', 'png', 'jpeg'];
                if (!in_array($fileext, $allowed)) {
                    header("location:add_product.php?err=1");
                } elseif ($size > $maxsize) {
                    header("location:add_product.php?err=2");
                } else {
                    $sql = "select product_image from product_detail where p_id = '$p_id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $image = $row["product_image"];
                    if ($image != "default.png") {
                        if (move_uploaded_file($tmp_name, $destination)) {
                            unlink($image);
                            $sql = "update product_detail set product_name = '$p_name',product_description='$p_descrip',product_price='$p_price',product_weight_qty='$p_weight',product_weight_unit='$p_unit',product_image='$relativePath',pc_id='$p_category' where p_id = '$p_id'";
                            mysqli_query($conn, $sql);
                            header("location:product_table.php");
                        }
                    } elseif (move_uploaded_file($tmp_name, $destination)) {
                        $sql = "update product_detail set product_name = '$p_name',product_description='$p_descrip',product_price='$p_price',product_weight_qty='$p_weight',product_weight_unit='$p_unit',product_image='$relativePath',pc_id='$p_category' where p_id = '$p_id'";
                        mysqli_query($conn, $sql);
                        header("location:product_table.php");
                    } else {
                        header("location:add_product.php?err=3");
                    }
                }
            } else {
                $sql = "update product_detail set product_name = '$p_name',product_description='$p_descrip',product_price='$p_price',product_weight_qty='$p_weight',product_weight_unit='$p_unit',pc_id='$p_category' where p_id = '$p_id'";
                mysqli_query($conn, $sql);
                header("location:product_table.php");
            }
            break;

        case 4:
            # Deleting records from product_detail 
            if (isset($_GET["p_id"])) {
                $p_id = $_GET["p_id"];
                $sql = "delete from product_detail where p_id = '$p_id'";
                $resut = mysqli_query($conn, $sql);
                if ($result == true) {
                    header("location:product_table.php");
                } else {
                    header("location:product_table.php?err=2");
                }
            } else {
                header("location:product_table.php?err=1");
            }
            break;


        case 5:
            # updating the status of product
            if (isset($_GET["p_id"]) && isset($_GET["key"])) {
                $p_id = $_GET["p_id"];
                $key = $_GET["key"];
                if ($key == 1) {
                    $sql = "update product_detail set product_status = 'F' where p_id ='$p_id'";
                    mysqli_query($conn, $sql);
                    header("location:product_table.php");
                } else {
                    $sql = "update product_detail set product_status = 'T' where p_id ='$p_id'";
                    mysqli_query($conn, $sql);
                    header("location:product_table.php");
                }
            } else {
                header("location:product_table.php?err=1");
            }
            break;

        case 6:
            # Deleting records from product_category 
            if (isset($_GET["pc_id"])) {
                $pc_id = $_GET["pc_id"];
                $checkQuery = "select * from product_detail where pc_id = '$pc_id'";
                $checkResult = mysqli_query($conn, $checkQuery);
                if (!mysqli_num_rows($checkResult)) {
                    $sql = "delete from product_category where pc_id = '$pc_id'";
                    $resut = mysqli_query($conn, $sql);
                    if ($result == true) {
                        header("location:category_table.php");
                    } else {
                        header("location:category_table.php?err=2");
                    }
                } else {
                    header("location:category_table.php?err=3");
                }
            } else {
                header("location:category_table.php?err=1");
            }
            break;


        case 7:
            # updating the status of product category
            if (isset($_GET["pc_id"]) && isset($_GET["key"])) {
                $pc_id = $_GET["pc_id"];
                $key = $_GET["key"];
                if ($key == 1) {
                    $sql = "update product_category set product_category_status = 'F' where pc_id ='$pc_id'";
                    mysqli_query($conn, $sql);
                    header("location:category_table.php");
                } else {
                    $sql = "update product_category set product_category_status = 'T' where pc_id ='$pc_id'";
                    mysqli_query($conn, $sql);
                    header("location:category_table.php");
                }
            } else {
                header("location:category_table.php?err=1");
            }
            break;

        case 8:
            #editing the records of product category
            if (isset($_POST["pc_id"])) {
                $pc_id = $_POST["pc_id"];
                $category_name = addslashes($_POST["p_category"]);
                $sql = "update product_category set product_category_name = '$category_name' where pc_id = '$pc_id'";
                mysqli_query($conn, $sql);
                header("location:category_table.php");
            }
            break;


        case 9:
            #admin login 
            $admin_email  = $_POST["admin_email"];
            $admin_password = md5($_POST["admin_password"]);

            $sql = "select * from admin_op where email = '$admin_email' and password = '$admin_password'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 1) {
                session_start();
                $row = mysqli_fetch_assoc($result);
                $_SESSION["admin_id"]  = $row["a_id"];
                $_SESSION["admin_name"] =  $row["name"];
                $_SESSION["admin_email"] = $row["email"];
                $_SESSION["admin_status"] = $row["admin_status"];
                header("location:dashboard.php");
            } else {
                header("location:admin_login.php?err=1");
            }
            break;


        case 10:
            #destroying the session for admin
            session_start();
            $_SESSION["admin_id"]  = "";
            $_SESSION["admin_name"] = "";
            $_SESSION["admin_email"] = "";
            $_SESSION["admin_status"] = "";
            session_unset();
            session_destroy();
            header("location:admin_login.php");
            break;


        case 11:
            #changing password for admin
            $curr_pass = md5($_POST["curr_pass"]);
            $new_pass  = md5($_POST["new_pass"]);
            $a_id      = $_POST["a_id"];

            $sql = "select password from admin_op where a_id = '$a_id'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 1) {
                $sql = "update admin_op set password = '$new_pass' where a_id = '$a_id'";
                mysqli_query($conn, $sql);
                header("location:dashboard.php?msg=100");
            } else {
                header("location:changepassword.php?err=1");
            }

            break;

        case 12:
            session_start();
            $cust_name = addslashes($_SESSION["user_name"]);
            $cust_email = $_SESSION["user_email"];
            $cust_mobno = $_SESSION["user_mobile"];
            $cust_password = md5($_SESSION["user_password"]); 
            $cust_address = addslashes($_SESSION["user_address"]);
            $cust_pincode = $_SESSION["user_pincode"];

            date_default_timezone_set('Asia/Kolkata');
            $signup_dtime = date('Y-m-d H:i:s');

    
            $sql = "SELECT cust_id FROM customer_op WHERE cust_email = '$cust_email' OR cust_mobno = '$cust_mobno'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                header("location:customer_signup.php?err=1"); 
                exit();
            } else {
                // Insert new customer
                $sql = "INSERT INTO customer_op (
                            cust_name, cust_email, cust_mobno, cust_password, cust_address, cust_pincode, cust_signup_dtime
                        ) VALUES (
                            '$cust_name', '$cust_email', '$cust_mobno', '$cust_password', '$cust_address', '$cust_pincode', '$signup_dtime'
                        )";

                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $cust_id = mysqli_insert_id($conn);
                    $address_sql = "INSERT INTO address_op (name, mobile, address, address_type, pincode, address_dtime, is_default, cust_id) VALUES ('$cust_name', '$cust_mobno', '$cust_address', 'home', '$cust_pincode', '$signup_dtime', 'T', '$cust_id')";

                    $address_result = mysqli_query($conn, $address_sql);

                    if ($address_result) {
                        $_SESSION["cust_email"] = $cust_email;
                        $_SESSION["cust_name"] = $cust_name;
                        $_SESSION["cust_mobno"] = $cust_mobno;

                        if (isset($_SESSION["p_id"])) {
                            header("location:../ordernow.php");
                        } else {
                            header("location:../customer_login.php?msg=100");
                        }
                    } else {
                        // Address insert failed
                        session_unset();
                        session_destroy();
                        header("location:../customer_signup.php?err=2");
                    }
                } else {
                    // Customer insert failed
                    session_unset();
                    session_destroy();
                    header("location:../customer_signup.php?err=3");
                }
            }
            break;


        case 13:
            #customer login
            $cust_email_mob = $_POST["user_email_mob"];
            $cust_password = md5($_POST["user_password"]);

            $sql = "select * from customer_op where (cust_email = '$cust_email_mob' or cust_mobno = '$cust_email_mob') and cust_password = '$cust_password'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                session_start();
                $_SESSION["cust_email"] = $row["cust_email"];
                $_SESSION["cust_name"] = $row["cust_name"];
                $_SESSION["cust_mobno"] = $row["cust_mobno"];
                $_SESSION["cust_id"] = $row["cust_id"];
                $_SESSION["cust_login"] = true;
                if (isset($_SESSION["p_id"])) {
                    header("location:../ordernow.php");
                } else {
                    header("location:../index.php");
                }
            } else {
                header("location:../customer_login.php?err=1");
            }
            break;


        case 14:
            #customer logout
            session_start();
            $_SESSION["cust_email"] = "";
            $_SESSION["cust_name"] = "";
            $_SESSION["cust_mobno"] = "";
            $_SESSION["cust_id"] = "";
            $_SESSION["p_id"] = "";
            session_unset();
            session_destroy();
            header("location:../index.php");
            break;

        case 15:
            #order table entry 
            $prod_id = $_POST["prod_id"];
            $cust_id = $_POST["cust_id"];
            $add_id = addslashes($_POST["delivery_address"]);
            $order_qty = $_POST["order_qty"];
            $order_price = $_POST["prod_price"];
            $total_price = $order_qty * $order_price;
            date_default_timezone_set('Asia/Kolkata');
            $order_dtime = date('Y-m-d h:i:s');
            $sql = "insert into main_order_table(cust_id,order_dtime,order_amount,delivery_address) values('$cust_id','$order_dtime','$total_price','$add_id')";
            mysqli_query($conn, $sql);


            $sql = "select order_id from main_order_table where cust_id = '$cust_id' order by order_dtime desc limit 1";
            $result = mysqli_query($conn, $sql);
            $row    = mysqli_fetch_assoc($result);
            $order_id = $row["order_id"];

            $sql = "insert into suborder_op(prod_id,order_qty,order_dtime,order_price,order_tamount,order_id) values('$prod_id','$order_qty','$order_dtime','$order_price','$total_price','$order_id')";
            $result = mysqli_query($conn, $sql);

            if ($result == true) {
                header("location:../index.php?msg=10");
            } else {
                header("location:../index.php?err=10");
            }

            break;

        case 16:
            # CUSTOMER CHANGING THE STATUS OF ORDER
            if (isset($_GET["order_id"])) {

                $order_id = $_GET["order_id"];
                $sql = "update main_order_table set order_status = 'C' where order_id = '$order_id'";
                $result = mysqli_query($conn, $sql);

                $sql = "update suborder_op set order_status = 'C' where order_id = '$order_id'";
                $result = mysqli_query($conn, $sql);

                if ($result == true) {
                    header("location:../myorder_table.php");
                } else {
                    header("location:../myorder_table.php");
                }
            }
            break;


        case 17:
            #changing password for customer
            $curr_pass = md5($_POST["curr_pass"]);
            $new_pass  = md5($_POST["new_pass"]);
            $cust_id      = $_POST["cust_id"];

            $sql = "select cust_password from customer_op where cust_id = '$cust_id' and cust_password = '$curr_pass'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 1) {
                $sql = "update customer_op set cust_password = '$new_pass' where cust_id = '$cust_id'";
                mysqli_query($conn, $sql);
                header("location:../index.php?msg=100");
            } else {
                header("location:../change_customerPassword.php?err=1");
            }

            break;

        case 18:
            # Editing customer profile
            session_start();
            $cust_id      = $_POST["cust_id"];
            $cust_name    = addslashes($_POST["cust_name"]);
            $cust_email   = $_POST["cust_email"];
            $cust_mobile  = $_POST["cust_mobile"];
            $cust_address = addslashes($_POST["cust_address"]);
            $cust_pincode = $_POST["cust_pincode"];

            $sql = "select * from customer_op where (cust_email ='$cust_email' or cust_mobno='$cust_mobile') and cust_id != '$cust_id'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0) {
                $sql = "update customer_op set cust_name='$cust_name',cust_email='$cust_email',cust_mobno='$cust_mobile',cust_address='$cust_address',cust_pincode='$cust_pincode' where cust_id='$cust_id'";
                $result = mysqli_query($conn, $sql);
                if ($result == true) {
                    header("location:../index.php?msg=100");
                    $_SESSION["cust_name"] = $cust_name;
                    $_SESSION["cust_email"] = $cust_email;
                    $_SESSION["cust_mobno"] = $cust_mobile;
                } else {
                    header("location:../customer_profile_edit.php?err=2");
                }
            } else {
                header("location:../customer_profile_edit.php?err=1");
            }
            break;

        case 19:
            # Editing admin profile
            session_start();
            $admin_id      = $_POST["admin_id"];
            $admin_name    = addslashes($_POST["admin_name"]);
            $admin_email   = $_POST["admin_email"];


            $sql = "select * from admin_op where email ='$admin_email' and a_id != '$admin_id'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0) {
                $sql = "update admin_op set name='$admin_name',email='$admin_email',where a_id='$admin_id'";
                $result = mysqli_query($conn, $sql);
                if ($result == true) {
                    header("location:index.php?msg=100");
                    $_SESSION["admin_name"] = $admin_name;
                    $_SESSION["admin_email"] = $admin_email;
                } else {
                    header("location:admin_profile_edit.php?err=2");
                }
            } else {
                header("location:admin_profile_edit.php?err=1");
            }
            break;


        case 20:
            # CUSTOMER CHANGING THE STATUS OF SUBORDER
            if (isset($_GET["sub_order_id"])) {
                $sub_order_id = $_GET["sub_order_id"];
                $sql = "update suborder_op set order_status = 'C' where sub_order_id = '$sub_order_id'";
                $result = mysqli_query($conn, $sql);

                if ($result == true) {
                    header("location:../myorder_table.php");
                } else {
                    header("location:../myorder_table.php");
                }
            }
            break;

        case 21:
            # admin updating the status of customer
            if (isset($_GET["cust_id"]) && isset($_GET["key"])) {
                $cust_id = $_GET["cust_id"];
                $key = $_GET["key"];
                if ($key == 1) {
                    $sql = "update customer_op set cust_status = 'F' where cust_id ='$cust_id'";
                    mysqli_query($conn, $sql);
                    header("location:all_customer_table.php");
                } else {
                    $sql = "update customer_op set cust_status = 'T' where cust_id ='$cust_id'";
                    mysqli_query($conn, $sql);
                    header("location:all_customer_table.php");
                }
            } else {
                header("location:all_customer_table?err=1");
            }
            break;

        case 22:
            # Admin CHANGING THE STATUS OF ORDER
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $order_id = $_POST['order_id'] ?? null;
                $sub_order_id = $_POST['sub_order_id'] ?? null;
                $reason = $_POST['reason'] ?? '';

                if (!empty($order_id) && empty($sub_order_id)) {
                    $sql = "update main_order_table set order_status = 'C',remark = '$reason' where order_id = '$order_id'";
                    mysqli_query($conn, $sql);
                    header("location:all_order_table.php");
                }
                if (!empty($order_id) && !empty($sub_order_id)) {
                    $sql = "update suborder_op set order_status = 'C',remark = '$reason' where order_id = '$order_id' and sub_order_id = '$sub_order_id'";
                    mysqli_query($conn, $sql);

                    $checkSql = "SELECT COUNT(*) AS remaining FROM suborder_op WHERE order_id = '$order_id' AND order_status != 'C'";
                    $checkResult = mysqli_query($conn, $checkSql);
                    $row = mysqli_fetch_assoc($checkResult);


                    if ($row['remaining'] == 0) {
                        $updateMain = "UPDATE main_order_table SET order_status = 'C' WHERE order_id = '$order_id'";
                        mysqli_query($conn, $updateMain);
                    }
                    header("location:all_order_table.php");
                }
            }
            break;

        case 23:
            #adding product to cart
            session_start();
            if (empty($_SESSION["cust_name"])) {
                header("location:../customer_login.php");
            }
            $p_id = $_POST["p_id"];
            $cust_id = $_SESSION["cust_id"];
            date_default_timezone_set('Asia/Kolkata');
            $dtime = date('Y-m-d h:i:s');

            $sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND cust_id = '$cust_id' AND cart_flag = 'F'";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0) {
                $sql = "insert into cart(p_id,cust_id,add_dtime) values('$p_id','$cust_id','$dtime')";
                mysqli_query($conn, $sql);
                header("location:../index.php?msg=1");
            } else {
                header("location:../index.php?err=45");
            }
            break;

        case 24:
            # customer ordering products from cart
            session_start();
            $cust_id     = $_SESSION['cust_id'];
            date_default_timezone_set('Asia/Kolkata');
            $dtime = date('Y-m-d h:i:s');
            $total = 0;
            $add_id = $_POST["delivery_address"];
            if (!empty($_POST['prod_selection'])) {
                $sql = "insert into main_order_table(cust_id,order_dtime,delivery_address) values('$cust_id','$dtime','$add_id')";
                mysqli_query($conn, $sql);

                $sql = "select order_id from main_order_table where cust_id = '$cust_id' order by order_dtime desc limit 1";
                $result = mysqli_query($conn, $sql);
                $row    = mysqli_fetch_assoc($result);
                $order_id = $row["order_id"];
                foreach ($_POST['prod_selection'] as $productId) {
                    $qty = $_POST['order_qty'][$productId] ?? 0;
                    $price = $_POST['prod_price'][$productId] ?? 0;
                    $subtotal = $qty * $price;
                    $total += $subtotal;

                    $sql = "insert into suborder_op(prod_id,order_qty,order_price,order_tamount,order_dtime,order_id) values('$productId','$qty','$price','$subtotal','$dtime','$order_id')";
                    mysqli_query($conn, $sql);

                    $sql2 = "update cart set cart_flag = 'T' where p_id = '$productId' and cust_id = '$cust_id'";
                    mysqli_query($conn, $sql2);
                }
                $sql = "update main_order_table set order_amount = '$total' where order_id = '$order_id'";
                mysqli_query($conn, $sql);
                header("location:../index.php?msg=10");
            } else {
                header("location:../index.php?err=10");
            }
            break;

        case 25:
            #deleting product from cart 
            session_start();
            if (isset($_GET["val"]) && !empty($_GET["val"])) {
                $p_id = $_GET["val"];
                $cust_id = $_SESSION["cust_id"];

                $sql = "delete from cart where p_id = '$p_id' and cust_id = '$cust_id'";
                mysqli_query($conn, $sql);
                header("location:../cart.php");
            } else {
                header("location:../cart.php");
            }
            break;

        case 26:
            #inserting address 
            if (isset($_POST["cust_id"])) {
                $cust_id = $_POST["cust_id"];
                $rname = addslashes($_POST['reciever_name']);
                $rmob = $_POST['reciever_mob'];
                $raddress = addslashes($_POST['reciever_address']);
                $rpin = $_POST['reciever_pincode'];
                $rtype = $_POST['address_type'];
                date_default_timezone_set('Asia/Kolkata');
                $dtime = date('Y-m-d h:i:s');
                $default = $_POST['checkbox'];

                if($default == 'T'){
                    $sql = "update address_op set is_default = 'F' where cust_id = '$cust_id'";
                    mysqli_query($conn,$sql);
                }

                $sql = "insert into address_op (name,mobile,address,pincode,address_type,cust_id,address_dtime,is_default) values('$rname','$rmob','$raddress','$rpin','$rtype','$cust_id','$dtime','$default')";
                mysqli_query($conn, $sql);
                header("location:../address.php?msg=1");
            } else {
                header("location:../address.php?err=1");
            }
            break;


        case 27:
            #customer deleting address
            if (isset($_GET["add_id"])) {
                $add_id = $_GET["add_id"];

                $sql = "delete from address_op where address_id = '$add_id'";
                mysqli_query($conn, $sql);
                header("location:../address_table.php");
            }
            header("location:../address_table.php");
            break;

        case 28:
            #customer updating the address 
            if (isset($_POST["add_id"])) {
                $add_id = $_POST["add_id"];
                $rname = addslashes($_POST['reciever_name']);
                $rmob = $_POST['reciever_mob'];
                $raddress = $_POST['reciever_address'];
                $rpin = addslashes($_POST['reciever_pincode']);
                $rtype = $_POST['address_type'];
                $sql = "update address_op set name = '$rname',mobile = '$rmob', address = '$raddress', pincode = '$rpin',address_type='$rtype' where address_id = '$add_id'";
                mysqli_query($conn, $sql);
                header("location:../address_table.php?msg=1");
            } else {
                header("location:../address_edit.php?err=1");
            }
            break;

        case 29:
            # customer setting default address

            session_start();
            if (isset($_GET['add_id'])) {
                $add_id = $_GET['add_id'];
                $cust_id = $_SESSION['cust_id'];

                $sql = "update address_op set is_default = 'F' where cust_id = '$cust_id'";
                mysqli_query($conn, $sql);

                $sql = "update address_op set is_default = 'T' where cust_id = '$cust_id' and address_id = '$add_id'";
                mysqli_query($conn, $sql);

                header("location:../address_table.php");
            }
            break;

        case 30:
            #admin changing the order status
            if (isset($_POST["order_id"])) {
                $order_id = $_POST["order_id"];
                $status = $_POST["order_status"];



                $sql = "update main_order_table set order_status = '$status' where order_id = '$order_id'";
                mysqli_query($conn, $sql);

                $sql = "update suborder_op set order_status = '$status' where order_id = '$order_id' and order_status != 'C'";
                mysqli_query($conn, $sql);

                header("location:manage_order.php?msg=1");
            } else {
                header("location:manage_order.php?err=1");
            }
            break;

        case 31:
        # forgot password logic
            $user_email = $_POST['user_email'];
            $user_password = md5($_POST['password']);

            $sql = "update customer_op set cust_password = '$user_password' where cust_email = '$user_email'";
            if(mysqli_query($conn,$sql)){
                session_unset();
                session_destroy();
                header("Location: ../customer_login.php?msg=200");
            }
            else{
                session_unset();
                session_destroy();
                header("Location: ../customer_login.php?err=1");
            }
        break;
    }
} else {
    die("Missing Flag value..");
}
