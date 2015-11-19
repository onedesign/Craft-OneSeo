<?php
namespace Craft;

/**
 * One SEO Meta Service
 */
class OneSeo_MetaService extends BaseApplicationComponent
{
  public $meta = array(
    'metaTitle' => [],
    'metaDescription' => '',
    'metaImage' => ''
  );

  public function setMetaTitle($title)
  {
    if (is_array($title))
    {
      $this->meta['metaTitle'] = $title;
    }
    elseif (is_string($title))
    {
      $this->meta['metaTitle'][0] = $title;
    }
  }

  public function getMetaTitle()
  {
    return $this->meta['metaTitle'];
  }

  public function setMetaDescription($description)
  {
    $this->meta['metaDescription'] = $description;
  }

  public function getMetaDescription()
  {
    return $this->meta['metaDescription'];
  }

  public function setMetaImage($imageUrl)
  {
    $this->meta['metaImage'] = $imageUrl;
  }

  public function getMetaImage()
  {
    return $this->meta['metaImage'];
  }
}