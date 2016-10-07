<?php
defined('_JEXEC') or die;
class FolioModelFolios extends JModelList
{
  public function __construct($config = array())
  {
    if (empty($config['filter_fields']))
    {
      $config['filter_fields'] = array('id', 'a.id',
      'title', 'a.title',
      'state', 'a.state',
      'company', 'a.company',
      'publish_up', 'a.publish_up',
      'publish_down', 'a.publish_down',
      'ordering', 'a.ordering'
    );
  }
  parent::__construct($config);
}
protected function populateState($ordering = null, $direction =null)
{
  //check to see what has been typed into the search in the title box, and assign this to filter.search
  $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
  $this->setState('filter.search', $search);
  //we fnd out which option of the status flter is selected, and assign it to a variable that we will use in our query
  $published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
  $this->setState('filter.state', $published);
  //sets the default column that we are sorting the view by when you frst open the component
  parent::populateState('a.ordering', 'asc');
}
protected function getListQuery()
{
  $db = $this->getDbo();
  $query = $db->getQuery(true);
  $query->select(
  $this->getState(
  'list.select',
  'a.id, a.title,'.
  'a.state, a.company,'.
  'a.publish_up, a.publish_down, a.ordering'
  )
);
/*
we will take a look at this status and adjust the selection from
our database based on the value. If nothing is selected, we will default to showing all
published and unpublished items (state = 1 or state = 0)
*/
$published = $this->getState('filter.state');
if (is_numeric($published))
{
  $query->where('a.state = '.(int) $published);
}
elseif ($published === '')
{
  $query->where('(a.state IN (0, 1))');
}
$query->from($db->quoteName('#__folio').' AS a');
// Filter by search in title
/*
check to see if any text has been entered in the Search in title
flter, and if so, we then check to see if id: has been entered, in which case we will
search for an item that matches the id entered
 */
$search = $this->getState('filter.search');
if (!empty($search))
{
  if (stripos($search, 'id:') === 0)
  {
    $query->where('a.id = '.(int) substr($search, 3));
  } else {
    $search = $db->Quote('%'.$db->escape($search, true).'%');
    $query->where('(a.title LIKE '.$search.' OR a.company LIKE '.$search.')');
  }
}
$orderCol = $this->state->get('list.ordering');//determine which column is clicked, and whether we should sort in ascending or descending order
$orderDirn = $this->state->get('list.direction');
$query->order($db->escape($orderCol.' '.$orderDirn));
return $query;
}
}
