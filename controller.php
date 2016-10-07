<?php
defined('_JEXEC') or die;
class FolioController extends JControllerLegacy
{
  protected $default_view = 'folios';//اگر نام view با نام کامپوننت یکی باشد نیازی به این خط نیست
  public function display($cachable = false, $urlparams = false)
  {
    require_once JPATH_COMPONENT.'/helpers/folio.php';
    $view = $this->input->get('view', 'folios');//بدست آوردن نام ویو
    $layout = $this->input->get('layout', 'default');//بدست آوردن نام لیوت
    $id = $this->input->getInt('id');
    if ($view == 'folio' && $layout == 'edit' && !$this->checkEditId('com_folio.edit.folio', $id))//چک کردن اینکه کسی مستقیما وارد صفحه نشود
    {
      $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));//تنظیم خطا
      $this->setMessage($this->getError(), 'error');//تنظیم پیام خطا
      $this->setRedirect(JRoute::_('index.php?option=com_folio&view=folios', false));
      return false;
    }
    parent::display();//نمایش قسمت های خود جوملا
    return $this;
  }
}
