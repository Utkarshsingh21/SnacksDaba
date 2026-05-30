<script>
  window.addEventListener('DOMContentLoaded', function () {
    const sidebarElement = document.getElementById('offcanvasSidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebar = new bootstrap.Offcanvas(sidebarElement);

    const SIDEBAR_WIDTH = 230;
    const BREAKPOINT = 768;

    function handleSidebarDisplay() {
      if (window.innerWidth >= BREAKPOINT) {
        sidebar.show();
        mainContent.style.marginLeft = `${SIDEBAR_WIDTH}px`;
      } else {
        sidebar.hide();
        mainContent.style.marginLeft = 0;
      }
    }

    // Initial run
    handleSidebarDisplay();

    // Rerun on resize
    window.addEventListener('resize', handleSidebarDisplay);

    // Listen to offcanvas events to update layout
    sidebarElement.addEventListener('shown.bs.offcanvas', function () {
      if (window.innerWidth >= BREAKPOINT) {
        mainContent.style.marginLeft = `${SIDEBAR_WIDTH}px`;
      }
    });

    sidebarElement.addEventListener('hidden.bs.offcanvas', function () {
      mainContent.style.marginLeft = 0;
    });
  });
</script>

<!-- Bootstrap JS and dependencies -->

</body>
</html>