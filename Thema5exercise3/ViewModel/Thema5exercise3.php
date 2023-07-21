<?php
namespace Perspective\Thema5exercise3\ViewModel;

class Thema5exercise3 implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $_productCollectionFactory;


    /**
     * @var Magento\Catalog\Model\CategoryFactory
     */
    private $_categoryFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_categoryFactory = $categoryFactory;

        
    }
    
    public function getProductCollectionByCategories($ids)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*')
        ->addAttributeToFilter('type_id', array('eq' => 'configurable'))
        ->addAttributeToFilter('price', array('gt' => 50))
        ->addAttributeToFilter('price', array('lt' => 60));
        $collection->addCategoriesFilter(['in' => $ids]);
        return $collection;
    }

    public function getCategoryName($categoryId)
    {
        $category = $this->_categoryFactory->create()->load($categoryId);
        $categoryName = $category->getName();
        return $categoryName;
    }


}
