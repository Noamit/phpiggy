<?php include $this->resolve("partials/_header.php"); ?>
<!-- Start Main Content Area -->
<section class="container mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
    <!-- Page Title -->
    <h3>Activities</h3>
    <div class="flex space-x-4">
        <a href="/category_chart"
            class="flex items-center p-2 bg-sky-50 text-xs text-sky-900 hover:bg-sky-500 hover:text-white transition rounded">
            category_chart
        </a>
        <a href="/month_chart"
            class="flex items-center p-2 bg-sky-50 text-xs text-sky-900 hover:bg-sky-500 hover:text-white transition rounded">
            month_chart
        </a>
    </div>



</section>
<!-- End Main Content Area -->

<?php include $this->resolve("partials/_footer.php"); ?>