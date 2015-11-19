<?php
namespace Craft;
class OneSeoVariable
{
    /**
     * Output combined meta information
     *
     * @return string
     */
    public function meta()
    {   
        $pluginSettings = craft()->plugins->getPlugin('oneSeo')->getSettings();

        $metaData = [
          'url' => craft()->getSiteUrl(),
          'pageTitle' => craft()->siteName
        ];

        $element = craft()->urlManager->getMatchedElement();

        if ($element && $element->getElementType() == ElementType::Entry)
        {
            // Look for a custom SEO title
            $pageTitle = $element->title;
            $customTitle = $element->seoTitle;
            if (strlen($customTitle)) $pageTitle = $customTitle;
            $metaData['pageTitle'] = $pageTitle;

            // Look for a custom SEO description in CMS
            $customDescription = $element->seoMetaDescription;
            if (strlen($customDescription)) $metaData['description'] = $customDescription;

            // Look for a custom SEO image
            $customImage = $element->seoImage;
            if ($customImage->count())
            {
                $metaData['image'] = $customImage[0]->url;
            }
            else
            {
                // Try to find existing images in the entry
                foreach ($element->getFieldLayout()->getFields() as $fieldLayoutField)
                {
                    $field = $fieldLayoutField->getField();
                    $type = $field->type;
                    if ($type == 'Assets')
                    {
                      $value = $element->getFieldValue($field->handle);
                      if ($value->count())
                      {
                        $metaData['image'] = $value[0]->url;
                        break;
                      }
                    }
                }
            }
            
            // Set URL
            $metaData['url'] = $element->url;
        }

        // Override defaults with template customizations
        // Title…
        $dividerChar = ' ' . $pluginSettings->titleDividerCharacter . ' ';
        $customTemplateTitle = craft()->oneSeo_meta->getMetaTitle();
        if ($customTemplateTitle) $metaData['pageTitle'] = implode($dividerChar, $customTemplateTitle);
        // Description…
        $customTemplateDescription = craft()->oneSeo_meta->getMetaDescription();
        if ($customTemplateDescription) $metaData['description'] = $customTemplateDescription;
        // Image…
        $customTemplateImage = craft()->oneSeo_meta->getMetaImage();
        if ($customTemplateImage) $metaData['image'] = $customTemplateImage;

        // Concatenate the full title
        if ($metaData['pageTitle'] != craft()->siteName) {
          $metaData['pageTitle'] = $metaData['pageTitle'] . $dividerChar . craft()->siteName;
        }


        $originalPath = craft()->path->getTemplatesPath();
        $pluginTemplatePath = craft()->path->getPluginsPath() . 'oneseo/templates';
        craft()->path->setTemplatesPath($pluginTemplatePath);
        $html = craft()->templates->render('meta', $metaData);
        craft()->path->setTemplatesPath($originalPath);
        echo $html;
    }
}