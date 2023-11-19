<?php include $this->resolve("partials/_header.php"); ?>
<section class="container mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <form method="GET" action="/" class="w-full">
            <div class="flex">
                <input name="c" type="text" value="<?php echo (string)$searchTerm;?>"
                    class="w-full rounded-l-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    placeholder="Enter search term" />
                <button type="submit"
                    class="rounded-r-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Search
                </button>
            </div>
        </form>

    </div>
</section>
<?php include $this->resolve("partials/_footer.php"); ?>