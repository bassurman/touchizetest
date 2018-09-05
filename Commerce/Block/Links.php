<?php
namespace Touchize\Commerce\Block;

class Links extends \Magento\Framework\View\Element\Template
{
    /**
     * @var array
     */
    protected $navigationLinks = [];

    /**
     * @param $label
     * @param $path
     * @param $type
     *
     * @return $this
     */
    public function addNavigationLink($label, $path, $type)
    {
        $this->navigationLinks[] = [
            'type' => $type,
            'title' => __($label),
            'url' => $this->getUrl($path),
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->navigationLinks;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return '';
    }
}