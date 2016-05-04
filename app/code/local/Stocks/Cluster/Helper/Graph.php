<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 3/14/16
 */

class Stocks_Cluster_Helper_Graph
{
    const GRAPH_POINT_REGEX = '/^[0-9]*_[0-9]*_[a-z]*$/';

    public function getOrderedGraphPoints($data_array)
    {
        $graph_point_fields = array();
        foreach($data_array as $key => $value)
        {
            if (preg_match(self::GRAPH_POINT_REGEX, $key))
            {
                $graph_point_fields[$key] = $value;
            }
        }

        uksort($graph_point_fields, 'Stocks_Cluster_Helper_Graph::compare_graph_point_keys');

        return $graph_point_fields;
    }

    /**
     * We want to sort desc here since a greater number will mean further in the past
     *
     * @param string $key_a
     * @param string $key_b
     * @return bool
     */
    static public function compare_graph_point_keys($key_a, $key_b)
    {
        $span_start_a = intval(explode('_', $key_a)[1]);
        $span_start_b = intval(explode('_', $key_b)[1]);

        return ($span_start_a < $span_start_b);
    }
}
