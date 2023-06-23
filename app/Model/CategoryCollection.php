<?php

namespace app\Model;

use app\Helper\Helper;
use JsonSerializable;

class CategoryCollection implements JsonSerializable
{
    /**
     * @var array
     */
    protected array $_collection = [];

    /**
     * @var \app\Helper\Helper
     */
    protected \app\Helper\Helper $_helper;

    /**
     * @param \app\Helper\Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * Add new Category to collection
     *
     * @param \app\Model\Category $category
     * @return void
     */
    public function addCategory(Category $category)
    {
        $this->_collection[] = $category;
    }

    /**
     * Find category by name
     *
     * @param string $categoryName
     * @return false|Category
     */
    public function findCategoryByName(string $categoryName)
    {
        $result = $this->_helper::searchObjectArray($this->_collection, $categoryName, "name");

        if ($result !== false && count($result) > 0) {
            return $result[0];
        }

        return false;
    }

    /**
     * Return a product inside a category.
     *
     * @param string $category
     * @return array
     */
    public function getProductsInCategory(string $category): array
    {

        $result = $this->findCategoryByName($category);

        if ($result !== false) {

            /**
             * $category Category
             */
            $category = $result;
            return $category->products;
        }
        return [];
    }

    /**
     * Check if product exists in category
     *
     * @param string $category
     * @param string $product
     * @return bool
     */
    public function doesProductExistInCategory(string $category, string $product): bool
    {

        $products = $this->getProductsInCategory($category);

        if (!empty($products)) {
            $productResult = $this->_helper::searchObjectArray($products, $product, "name");
            if ($productResult !== false && count($productResult) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns collection of categories
     *
     * @return array|mixed
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    public function getCollectionSummary(): array
    {
        $summrayData = [];
        /**
         * @var $category Category
         */
        foreach ($this->_collection as $category) {
            $summrayData[] = $category->getSummaryData();
        }

        return $summrayData;
    }

    public function jsonSerialize(): mixed
    {
        $returnData = [];
        foreach ($this->_collection as $category) {
            $tmpCategory = [];
            $tmpCategory["name"] = $category->name;
            $tmpCategory["products"] = [];
            foreach ($category->products as $product){
                $tmpCategory["products"][] = (array) $product;
            }

            $returnData[] = $tmpCategory;
        }

        return $returnData;
    }
}