<?php
defined('_JEXEC') or die;
$user = JFactory::getUser();//we need to know who the current user is
//we actually set the value of $listOrder and $listDirn variables based on the state.
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
//we need to do is check to make sure that the current user has permission to change the order of the items in the view.
$canOrder = $user->authorise('core.edit.state', 'com_folio');
//we need to check to see if we are sorting by the ordering column, so we know whether the drag-and-drop ordering should be enabled or disabled
$saveOrder = $listOrder == 'a.ordering';
/*
If we are sorting by the ordering column, we defne a URL which is going to be
used to save the ordering. This URL actually calls the saveOrderAjax function,
which we will need to defne in our controller for this view.
*/
if ($saveOrder)
{
  $saveOrderingUrl = 'index.php?option=com_folio&task=folios.saveOrderAjax&tmpl=component';
  JHtml::_('sortablelist.sortable', 'folioList', 'adminForm',
  strtolower($listDirn), $saveOrderingUrl);
}
//Note that the name folioList in the previous code matches the id we gave to our table
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
//There is a standard JavaScript function that need to add in for the ordering.
Joomla!.orderTable = function()
{
  table = document.getElementById("sortTable");
  direction = document.getElementById("directionTable");
  order = table.options[table.selectedIndex].value;
  if (order != '<?php echo $listOrder; ?>')
  {
    dirn = 'asc';
  }
  else
  {
    dirn = direction.options[direction.selectedIndex].value;
  }
  Joomla!.tableOrdering(order, dirn, '');
}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_folio&view=folios'); ?>" method="post" name="adminForm" id="adminForm">
  <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2"><!--determine where this flter sidebar is displayed-->
      <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
      <div id="j-main-container">
      <?php endif;?>
      <!--div for our flter bar-->
      <div id="filter-bar" class="btn-toolbar">
        <!--allows a user to type in text and search for items in the view that contain that word-->
        <div class="filter-search btn-group pull-left">
          <label for="filter_search" class="element-invisible">
            <?php echo JText::_('COM_FOLIO_SEARCH_IN_TITLE');?>
          </label>
          <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_FOLIO_SEARCH_IN_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_FOLIO_SEARCH_IN_TITLE'); ?>" />
        </div>
        <div class="btn-group pull-left">
          <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
            <i class="icon-search"></i>
          </button>
          <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();">
            <i class="icon-remove"></i>
          </button>
        </div>
        <!-- can adjust the number of items that appear on each page-->
        <div class="btn-group pull-right hidden-phone">
          <label for="limit" class="element-invisible">
            <?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?>
          </label>
          <?php echo $this->pagination->getLimitBox(); ?>
        </div>
        <div class="btn-group pull-right hidd en-phone">
          <label for="directionTable" class="element-invisible">
            <?php echo JText::_('JFIELD_ORDERING_DESC');?>
          </label>
          <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla!.orderTable()">
            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
            <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
            <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
          </select>
        </div>
        <div class="btn-group pull-right">
          <label for="sortTable" class="element-invisible">
           <?php echo JText::_('JGLOBAL_SORT_BY');?>
          </label>
          <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla!.orderTable()">
            <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
            <?php echo JHtml::_('select.options', $sortFields, 'value','text', $listOrder);?>
          </select>
            </div>
          </div>
          <div class="clearfix"> </div>
          <table class="table table-striped" id="folioList">
            <thead>
              <tr>
                <th width="1%" class="nowrap center hidden-phone">
                  <?php echo JHtml::_('grid.sort', '<i class="iconmenu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc','JGRID_HEADING_ORDERING'); ?>
                </th>
                <th width="1%" class="hidden-phone">
                  <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th width="1%" style="min-width:55px" class="nowrap center">
                  <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state',$listDirn, $listOrder); ?>
                </th>
                <th class="title">
                  <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE','a.title', $listDirn, $listOrder); ?>
                </th>
                <th width="25%" class="nowrap hidden-phone">
                  <?php echo JHtml::_('grid.sort', 'COM_FOLIO_HEADING_COMPANY', 'a.company', $listDirn, $listOrder); ?>
                </th>
                <th width="1%" class="nowrap center hidden-phone">
                  <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID','a.id', $listDirn, $listOrder); ?>
                </th>
              </tr>
            </thead>
            <!-- pagination-> used when there is more than one page-->
            <tfoot>
              <tr>
                <td colspan="10">
                  <?php //echo $this->pagination->getListFooter(); ?>
                </td>
              </tr>
            </tfoot>
            <tbody>
              <?php foreach ($this->items as $i => $item) :
                /*
                we need to check to see if the user has permission to publish or
                unpublish that item. Also, if the item is checked out to someone else, we don't want
                anyone else making changes until it is checked back in
                */
                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
                $canChange = $user->authorise('core.edit.state', 'com_folio')&& $canCheckin;
                ?>

                <tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
                  <td class="order nowrap center hidden-phone">
                    <?php if ($canChange) :
                      $disableClassName = '';
                      $disabledLabel = '';
                      if (!$saveOrder) :
                        $disabledLabel = JText::_('JORDERINGDISABLED');
                        $disableClassName = 'inactive tip-top';
                      endif; ?>
                      <span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
                        <i class="icon-menu"></i>
                      </span>
                      <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 textarea-order " />
                    <?php else : ?>
                      <span class="sortable-handler inactive" >
                        <i class="icon-menu"></i>
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="center hidden-phone">
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                  </td>
                  <td class="center">
                    <!--$canChange variable we have just set to thestatus feld in each row-->
                    <?php echo JHtml::_('jgrid.published', $item->state, $i,'folios.', '$canChange', 'cb', $item->publish_up, $item->publish_down); ?>
                  </td>
                  <td class="nowrap has-context">
                    <a href="<?php echo JRoute::_('index.php?option=com_folio&task=folio.edit&id='.(int) $item->id); ?>">
                      <?php echo $this->escape($item->title); ?>
                    </a>
                  </td>
                  <td class="hidden-phone">
                    <?php echo $this->escape($item->company); ?>
                  </td>
                  <td class="center hidden-phone">
                    <?php echo (int) $item->id; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <input type="hidden" name="task" value="" />
          <input type="hidden" name="boxchecked" value="0" />
          <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
          <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
          <?php echo JHtml::_('form.token'); ?>
        </div>
      </form>
