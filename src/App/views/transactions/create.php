<?php include $this->resolve("partials/_header.php"); ?>

<section class="max-w-2xl mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
    <form method="POST" class="grid grid-cols-1 gap-6">
        <?php include $this->resolve("partials/_csrf.php"); ?>

        <label class="block">
            <span class="text-gray-700">Description</span>
            <input value="<?php echo $oldFormData['description'] ?? ''; ?>" name="description" type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            <?php if (array_key_exists('description', $errors)) : ?>
            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php echo $errors['description'][0]; ?>
            </div>
            <?php endif; ?>
        </label>
        <label class="block">
            <span class="text-gray-700">Amount</span>
            <input value="<?php echo $oldFormData['amount'] ?? ''; ?>" name="amount" type="number" step="0.01"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            <?php if (array_key_exists('amount', $errors)) : ?>
            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php echo $errors['amount'][0]; ?>
            </div>
            <?php endif; ?>
        </label>
        <label class="block">
            <span class="text-gray-700">Date</span>
            <input value="<?php echo $oldFormData['date'] ?? ''; ?>" name="date" type="date"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            <?php if (array_key_exists('date', $errors)) : ?>
            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php echo $errors['date'][0]; ?>
            </div>
            <?php endif; ?>
        </label>
        <!-- Categories -->
        <label class="block">
            <span class="text-gray-700">Categories</span>
            <?php foreach($categories as $category): ?>
            <div class="form-check">
                <input class="form-check-input" name="<?php echo $category['category_name']?>" type="checkbox"
                    <?php echo (isset($_POST[$category['category_name']])?"value='y'":"value='n'")?>
                    <?php echo (isset($_POST[$category['category_name']])?"checked":"") ?> id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    <?php echo $category['category_name']?>
                </label>
            </div>
            <?php endforeach;?>
        </label>
        <button type="submit" class="block w-full py-2 bg-indigo-600 text-white rounded">
            Submit
        </button>
    </form>
</section>

<?php include $this->resolve("partials/_footer.php"); ?>