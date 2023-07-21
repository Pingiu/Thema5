<?php
namespace Perspective\CategoriesFromProduct\ViewModel;

class CategoriesFromProduct implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected $_categoryCollectionFactory;
    protected $_productRepository;
    protected $_registry;
        
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $_productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status 
     */
    private $_productStatus;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    private $_productVisibility;

    public function __construct(      
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status  $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility

        )
        {
            $this->_categoryCollectionFactory = $categoryCollectionFactory;
            $this->_productRepository = $productRepository;
            $this->_registry = $registry;
            $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productStatus = $productStatus;
        $this->_productVisibility = $productVisibility;
    }
    
    /**
     * Get category collection
     *
     * @param bool $isActive
     * @param bool|int $level
     * @param bool|string $sortBy
     * @param bool|int $pageSize
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection or array
     */
    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');        
        
        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }
                
        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }
        
        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }
        
        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize); 
        }    
        
        return $collection;
    }
    
    public function getProductById($id)
    {        
        return $this->_productRepository->getById($id);
    }
    
    public function getCurrentProduct()
    {        
        return $this->_registry->registry('current_product');
    }

    public function getAllActiveCatalogRule()
    {
        $catalogActiveRule = $this->_categoryCollectionFactory->create()->addFieldToFilter('is_active', 1);

        return $catalogActiveRule;
    }
    public function getProductCollectionByCategories($ids)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $ids]);
        return $collection;
    }


    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
        $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
        return $collection;
    }
}



