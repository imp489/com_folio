<?php
defined('_JEXEC') or die;
if (!JFactory::getUser()->authorise('core.manage', 'com_folio'))//چک کردن اینکه آیا کاربر جاری اجازه کار با کامپوننت را دارد یا خیر؟
{
  return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
$controller = JControllerLegacy::getInstance('Folio');//یک نمونه ازکلاس می سازد
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
