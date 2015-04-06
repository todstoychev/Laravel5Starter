<?php

namespace Lib;

class TableSorter {
    /**
     * Table column sorter
     * 
     * @param string $action "Controller@action"
     * @param string $col_name Column name to show in the table
     * @param string $param Parameter to order(databese table column name)
     * @param string $order Order direction 
     * @param integer $limit Items per page
     * @param string $search Search keyword
     * @return string
     */
    public static function sort($action, $col_name, $param, $order = 'asc', $limit = 10) {
        If ($order == 'asc') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }
        
        echo '<a href="' . action($action) . '?limit=' . $limit . '&param=' . $param . '&order=' . $order . '">' . $col_name . '</a>';
    }
    
    /**
     * Table column sorter
     * 
     * @param string $action "Controller@action"
     * @param string $col_name Column name to show in the table
     * @param string $param Parameter to order(databese table column name)
     * @param string $order Order direction 
     * @param string $search Search keyword
     * @return string
     */
    public static function sort_search($action, $col_name, $search = null, $param = null, $order = 'asc') {
        If ($order == 'asc') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }
        
        echo '<a href="' . action($action) . '?search=' . $search . '&param=' . $param . '&order=' . $order . '">' . $col_name . '</a>';
    }
    
    
}
