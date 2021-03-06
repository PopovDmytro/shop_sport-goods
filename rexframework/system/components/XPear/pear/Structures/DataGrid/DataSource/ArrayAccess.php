<?php
/**
 * Array Data Source Driver
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 *
 * Copyright (c) 1997-2007, Andrew Nagy <asnagy@webitecture.org>,
 *                          Olivier Guilyardi <olivier@samalyse.com>,
 *                          Mark Wiesemann <wiesemann@php.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *    * The names of the authors may not be used to endorse or promote products
 *      derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * CSV file id: $Id: Array.php,v 1.30 2007/01/24 19:55:53 wiesemann Exp $
 *
 * @version  $Revision: 1.30 $
 * @package  Structures_DataGrid_DataSource_Array
 * @category Structures
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 */

require_once 'Structures/DataGrid/DataSource.php';

/**
 * Array Data Source Driver
 *
 * This class is a data source driver for a 2D Array
 *
 * @version  $Revision: 1.30 $
 * @author   Olivier Guilyardi <olivier@samalyse.com>
 * @author   Andrew Nagy <asnagy@webitecture.org>
 * @author   Mark Wiesemann <wiesemann@php.net>
 * @access   public
 * @package  Structures_DataGrid_DataSource_Array
 * @category Structures
 */
class Structures_DataGrid_DataSource_ArrayAccess
    extends Structures_DataGrid_DataSource
{
    /**
     * The array
     *
     * @var RexDBList
     */
    private $_ar = array();

    function Structures_DataGrid_DataSource_ArrayAccess()
    {
        parent::Structures_DataGrid_DataSource();
    }

    /**
     * Bind
     *
     * @param   array $ar       The result object to bind
     * @access  public
     * @return  mixed           True on success, PEAR_Error on failure
     */
    function bind($ar, $options = array())
    {
        if ($options) {
            $this->setOptions($options);
        }

        if ($ar instanceof ArrayAccess) {
            $this->_ar = $ar;
            return true;
        } else {
            return PEAR::staticRaiseError('The provided source must be an ArrayAccess');
        }
    }

    /**
     * Count
     *
     * @access  public
     * @return  int         The number or records
     */
    function count()
    {
        return count($this->_ar);
    }

    /**
     * Fetch
     *
     * @param   integer $offset     Limit offset (starting from 0)
     * @param   integer $len        Limit length
     * @access  public
     * @return  array       The 2D Array of the records
     */
    function &fetch($offset = 0, $len = null)
    {
        if ($this->_ar && !$this->_options['fields'] && sizeof($this->_ar)) {
            $fields = array();

            $this->_ar->rewind();
            $current = $this->_ar->current();
            foreach ($current as $key => $value) {
                $fields[] = $key;
            }

            $this->setOptions(array('fields' => $fields));
        }

        $size = sizeof($this->_ar);
        if ($offset >= $size) {
            $arr = array();
            return $arr;
        }

        if (is_null($len)) {
            $len = $size;
        } else {
            $len += $offset;
        }
        if ($len > $size) {
            $len = $size;
        }

        for ($i = $offset; $i < $len; ++$i) {
            $buf = array();
            $this->_ar->rewind($i);
            $current = $this->_ar->current();
            /*foreach ($current as $key => $val) {
                if (in_array($key, $this->_options['fields'])) {
                    $buf[$key] = $val;
                }
            }
            $records[] = $buf;*/
            $records[] = $current;
        }

        return $records;
    }

    /**
     * Sorts the array.
     *
     * @access  public
     * @param   string  $sortField  Field to sort by
     * @param   string  $sortDir    Sort direction: 'ASC' or 'DESC'
     *                              (default: ASC)
     */
    function sort($sortField, $sortDir = null)
    {
        return false;
    }
}

/* vim: set expandtab tabstop=4 shiftwidth=4: */
?>
