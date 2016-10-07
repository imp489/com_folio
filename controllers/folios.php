<?php
defined('_JEXEC') or die;
class FolioControllerFolios extends JControllerAdmin
{
  public function getModel($name = 'Folio', $prefix = 'FolioModel', $config = array('ignore_request' => true))
  {
    $model = parent::getModel($name, $prefix, $config);
    return $model;
  }
  /*
  we need to add a function to our controller that will actually save the changes
  when we drag-and-drop items, and change the order
   */
  public function saveOrderAjax()
  {
    $input = JFactory::getApplication()->input;
    $pks = $input->post->get('cid', array(), 'array');
    $order = $input->post->get('order', array(), 'array');
    JArrayHelper::toInteger($pks);
    JArrayHelper::toInteger($order);
    $model = $this->getModel();
    $return = $model->saveorder($pks, $order);
    if ($return)
    {
      echo "1";
    }
    JFactory::getApplication()->close();
  }
}
