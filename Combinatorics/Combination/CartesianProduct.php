<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2014, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace {

from('Hoa')

/**
 * \Hoa\Iterator
 */
-> import('Iterator.~')

/**
 * \Hoa\Iterator\Map
 */
-> import('Iterator.Map')

/**
 * \Hoa\Iterator\IteratorIterator
 */
-> import('Iterator.~~');

}

namespace Hoa\Math\Combinatorics\Combination {

/**
 * Class \Hoa\Math\Combinatorics\Combination\CartesianProduct.
 *
 * Cartesian n-ary product iterator:
 *     X = {1, 2}
 *     Y = {a, b}
 *     Z = {A, B, C}
 *     X × Y × Z = { (1, a, A), (2, a, A), (1, b, A), (2, b, A)
 *                   (1, a, B), (2, a, B), (1, b, B), (2, b, B)
 *                   (1, a, C), (2, a, C), (1, b, C), (2, b, C) }
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Ivan Enderlin.
 * @license    New BSD License
 */

class CartesianProduct implements \Hoa\Iterator {

    /**
     * All sets.
     *
     * @var \Hoa\Math\Combinatorics\Combination\CartesianProduct array
     */
    protected $_sets = array();

    /**
     * Number of sets.
     *
     * @var \Hoa\Math\Combinatorics\Combination\CartesianProduct int
     */
    protected $_max       = 0;

    /**
     * Key.
     *
     * @var \Hoa\Math\Combinatorics\Combination\CartesianProduct int
     */
    protected $_key       = 0;

    /**
     * Current (contains the current t-uple).
     *
     * @var \Hoa\Math\Combinatorics\Combination\CartesianProduct array
     */
    protected $_current   = null;

    /**
     * Whether the iterator has reached the end or not.
     *
     * @var \Hoa\Math\Combinatorics\Combination\CartesianProduct bool
     */
    protected $_break     = true;



    /**
     * Constructor.
     *
     * @access  public
     * @param   \Traversable  $set    Set.
     * @param   …             …       …
     * @return  void
     */
    public function __construct ( $set ) {

        foreach(func_get_args() as $s) {

            if(is_array($s))
                $s = new \Hoa\Iterator\Map($s);
            else
                $s = new \Hoa\Iterator\IteratorIterator($s);

            $this->_sets[] = $s;
        }

        $this->_max   = count($this->_sets) - 1;
        $this->_break = empty($this->_sets);

        return;
    }

    /**
     * Get the current value.
     *
     * @access  public
     * @return  array
     */
    public function current ( ) {

        return $this->_current;
    }

    /**
     * Prepare the current value.
     *
     * @access  protected
     * @return  void
     */
    protected function _current ( ) {

        $this->_current = array();

        foreach($this->_sets as $set)
            $this->_current[] = $set->current();

        return;
    }

    /**
     * Get the current key.
     *
     * @access  public
     * @return  int
     */
    public function key ( ) {

        return $this->_key;
    }

    /**
     * Advance the internal collection pointer, and return the current value.
     *
     * @access  public
     * @return  array
     */
    public function next ( ) {

        for($i = 0; $i <= $this->_max; ++$i) {

            $this->_sets[$i]->next();

            if(false !== $this->_sets[$i]->valid())
                break;

            $this->_sets[$i]->rewind();

            if($i === $this->_max) {

                $this->_break = true;
                break;
            }
        }

        ++$this->_key;
        $this->_current();

        return $this->current();
    }

    /**
     * Rewind the internal collection pointer, and return the first collection.
     *
     * @access  public
     * @return  array
     */
    public function rewind ( ) {

        $this->_break = empty($this->_sets);
        $this->_key   = 0;

        foreach($this->_sets as $set)
            $set->rewind();

        $this->_current();

        return $this->current();
    }

    /**
     * Check if there is a current element after calls to the rewind() or the
     * next() methods.
     *
     * @access  public
     * @return  bool
     */
    public function valid ( ) {

        return false === $this->_break;
    }
}

}
