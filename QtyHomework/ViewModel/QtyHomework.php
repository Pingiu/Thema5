<?php
namespace Perspective\QtyHomework\ViewModel;

class QtyHomework implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    private $_stockItemRepository;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $_productFactory;

    /**
     * @var  \Magento\Catalog\Helper\Image 
     */
    private $_productImageHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface 
     */
    private $_productRepository;

    /**
     * @var \Magento\Customer\Model\CustomerFactory 
     */
    private $_customerFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory 
     */
    private $_orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection 
     */
    private $_cutomerGroup;

    /**
     * @var \Magento\Payment\Helper\Data 
     */
    private $_helperData;

    /**
     * @var \Magento\Shipping\Model\Config\Source\Allmethods 
     */
    private $_allmethods;

    public function __construct(
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Catalog\Model\ProductFactory $productFactory,
         \Magento\Catalog\Helper\Image  $productImageHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface  $productRepository,
        \Magento\Customer\Model\CustomerFactory  $customerFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory  $orderCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Group\Collection  $cutomerGroup,
        \Magento\Payment\Helper\Data  $helperData,
        \Magento\Shipping\Model\Config\Source\Allmethods  $allmethods
    )
    {
        $this->_stockItemRepository = $stockItemRepository;
        $this->_productFactory = $productFactory;
        $this->_productImageHelper = $productImageHelper;
        $this->_productRepository = $productRepository;
        $this->_customerFactory = $customerFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_cutomerGroup = $cutomerGroup;
        $this->_helperData = $helperData;
        $this->_allmethods = $allmethods;
        
    }
    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }

    public function getProductImageUrl($id)
    {
        $product = $this->_productFactory->create()->load($id);
        $url = $this->_productImageHelper->init($product, 'product_thumbnail_image')->getUrl();
        return $url;
    }

    public function getImageOriginalWidth($product, $imageId, $attributes = [])
    {
        return $this->_productImageHelper->init($product, $imageId, $attributes)->getWidth();
    }

    public function getImageOriginalHeight($product, $imageId, $attributes = [])
    {
        return $this->_productImageHelper->init($product, $imageId, $attributes)->getHeight();
    } 

    public function getCustomerCollection()
    {
        $collection = $this->_customerFactory->create()->getCollection()->load();

        return $collection;
    }

    public function getOrderCollection()    {     
        $collection = $this->_orderCollectionFactory->create()
          ->addAttributeToSelect('*')->setOrder('entity_id', 'desc');
            return $collection;
           }


    public function getCustomerGroups() {
        $customerGroups = $this->_cutomerGroup->toOptionArray();
        array_unshift($customerGroups, array('value'=>'', 'label'=>'Any'));
        return $customerGroups;
    }

    public function getAllPaymentMethodsList() {

        return $this->_helperData->getPaymentMethodList();

    }

    public function getAllShippingMethods()
    {
        return $this->_allmethods->toOptionArray();
    }
}
