<?php
namespace Craft;

/**
 * ODC SEO Service
 */
class OdcSeoService extends BaseApplicationComponent
{
  protected $seoGroupId;

  public function run() {
    $this->_createSeoFields();
  }

  private function _createSeoFields() {

    //
    //
    //  Create the Field Group
    //
    //////////////////////////////////////////////////////////

    OdcSeoPlugin::log('Creating the SEO field group.');

    $group = new FieldGroupModel();
    $group->name = 'SEO';

    if (craft()->fields->saveGroup($group))
    {
      OdcSeoPlugin::log('SEO group created successfully.' . $group->id);
      $this->seoGroupId = $group->id;
      OdcSeoPlugin::log('seoGroupId set to ' . $this->seoGroupId);
    }
    else
    {
      OdcSeoPlugin::log('Could not save the field group.', LogLevel::Warning);
    }

    //
    //
    //  Create the Title Field
    //
    //////////////////////////////////////////////////////////

    OdcSeoPlugin::log('Creating the SEO title field');

    $seoTitleField = new FieldModel();
    $seoTitleField->groupId        = $group->id;
    $seoTitleField->name           = 'SEO Title';
    $seoTitleField->handle         = 'seoTitle';
    $seoTitleField->instructions   = 'Will be added to the <title> for the page. If not set, the Title for the entry will be used instead.';
    $seoTitleField->translatable   = true;
    $seoTitleField->type           = 'PlainText';
    $seoTitleField->settings       = array(
      'multiline' => '1',
      'placeholder' => '',
      'maxLength' => '',
      'initialRows' => ''
    );

    if (craft()->fields->saveField($seoTitleField))
    {
      OdcSeoPlugin::log('SEO Title field created successfully.');
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO Title Field.', LogLevel::Warning);
    }

    //
    //
    //  Create the Description Field
    //
    //////////////////////////////////////////////////////////

    OdcSeoPlugin::log('Creating the SEO meta description');

    $seoMetaDescriptionField = new FieldModel();
    $seoMetaDescriptionField->groupId        = $group->id;
    $seoMetaDescriptionField->name           = 'SEO Meta Description';
    $seoMetaDescriptionField->handle         = 'seoMetaDescription';
    $seoMetaDescriptionField->instructions   = 'Will be used for the <meta name="description"> for the page';
    $seoMetaDescriptionField->translatable   = true;
    $seoMetaDescriptionField->type           = 'PlainText';
    $seoMetaDescriptionField->settings       = array(
      'multiline' => '1',
      'placeholder' => '',
      'maxLength' => '',
      'initialRows' => ''
    );

    if (craft()->fields->saveField($seoMetaDescriptionField))
    {
      OdcSeoPlugin::log('SEO Meta field created successfully.');
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO meta description field.', LogLevel::Warning);
    }

    //
    //
    //  Create the Open Graph Image Field
    //
    //////////////////////////////////////////////////////////

    OdcSeoPlugin::log('Creating the SEO og:image field');

    $seoImageField = new FieldModel();
    $seoImageField->groupId        = $group->id;
    $seoImageField->name           = 'SEO Image';
    $seoImageField->handle         = 'seoImage';
    $seoTitleField->instructions   = 'Will be used as the image representing the page when shared via social media.';
    $seoImageField->translatable   = false;
    $seoImageField->type           = 'Assets';
    $seoImageField->settings       = array(
      'useSingleFolder' => '',
      'defaultUploadLocationSubpath' => '',
      'singleUploadLocationSubpath' => '',
      'restrictFiles' => '1',
      'allowedKinds' => ['image'],
      'limit' => '1',
      'sectionLabel' => 'Add Image'
    );

    if (craft()->fields->saveField($seoImageField))
    {
      OdcSeoPlugin::log('SEO og:image field created successfully.');
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO og:image field.', LogLevel::Warning);
    }
  }
}