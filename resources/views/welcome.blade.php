<?php


/**
 * Website: http://sourceforge.net/projects/simplehtmldom/
 * Additional projects: http://sourceforge.net/projects/debugobject/
 * Acknowledge: Jose Solorzano (https://sourceforge.net/projects/php-html/)
 *
 * Licensed under The MIT License
 * See the LICENSE file in the project root for more information.
 *
 * Authors:
 *   S.C. Chen
 *   John Schlick
 *   Rus Carroll
 *   logmanoriginal
 *
 * Contributors:
 *   Yousuke Kumakura
 *   Vadim Voituk
 *   Antcs
 *
 * Version Rev. 1.9.1 (291)
 */

define('HDOM_TYPE_ELEMENT', 1);
define('HDOM_TYPE_COMMENT', 2);
define('HDOM_TYPE_TEXT', 3);
define('HDOM_TYPE_ENDTAG', 4);
define('HDOM_TYPE_ROOT', 5);
define('HDOM_TYPE_UNKNOWN', 6);
define('HDOM_QUOTE_DOUBLE', 0);
define('HDOM_QUOTE_SINGLE', 1);
define('HDOM_QUOTE_NO', 3);
define('HDOM_INFO_BEGIN', 0);
define('HDOM_INFO_END', 1);
define('HDOM_INFO_QUOTE', 2);
define('HDOM_INFO_SPACE', 3);
define('HDOM_INFO_TEXT', 4);
define('HDOM_INFO_INNER', 5);
define('HDOM_INFO_OUTER', 6);
define('HDOM_INFO_ENDSPACE', 7);

defined('DEFAULT_TARGET_CHARSET') || define('DEFAULT_TARGET_CHARSET', 'UTF-8');
defined('DEFAULT_BR_TEXT') || define('DEFAULT_BR_TEXT', "\r\n");
defined('DEFAULT_SPAN_TEXT') || define('DEFAULT_SPAN_TEXT', ' ');
defined('MAX_FILE_SIZE') || define('MAX_FILE_SIZE', 600000);
define('HDOM_SMARTY_AS_TEXT', 1);

function file_get_html(
	$url,
	$use_include_path = false,
	$context = null,
	$offset = 0,
	$maxLen = -1,
	$lowercase = true,
	$forceTagsClosed = true,
	$target_charset = DEFAULT_TARGET_CHARSET,
	$stripRN = true,
	$defaultBRText = DEFAULT_BR_TEXT,
	$defaultSpanText = DEFAULT_SPAN_TEXT)
{
	if($maxLen <= 0) { $maxLen = MAX_FILE_SIZE; }

	$dom = new simple_html_dom(
		null,
		$lowercase,
		$forceTagsClosed,
		$target_charset,
		$stripRN,
		$defaultBRText,
		$defaultSpanText
	);

	/**
	 * For sourceforge users: uncomment the next line and comment the
	 * retrieve_url_contents line 2 lines down if it is not already done.
	 */
	$contents = file_get_contents(
		$url,
		$use_include_path,
		$context,
		$offset,
		$maxLen
	);
	// $contents = retrieve_url_contents($url);

	if (empty($contents) || strlen($contents) > $maxLen) {
		$dom->clear();
		return false;
	}

	return $dom->load($contents, $lowercase, $stripRN);
}

function str_get_html(
	$str,
	$lowercase = true,
	$forceTagsClosed = true,
	$target_charset = DEFAULT_TARGET_CHARSET,
	$stripRN = true,
	$defaultBRText = DEFAULT_BR_TEXT,
	$defaultSpanText = DEFAULT_SPAN_TEXT)
{
	$dom = new simple_html_dom(
		null,
		$lowercase,
		$forceTagsClosed,
		$target_charset,
		$stripRN,
		$defaultBRText,
		$defaultSpanText
	);

	if (empty($str) || strlen($str) > MAX_FILE_SIZE) {
		$dom->clear();
		return false;
	}

	return $dom->load($str, $lowercase, $stripRN);
}

function dump_html_tree($node, $show_attr = true, $deep = 0)
{
	$node->dump($node);
}

class simple_html_dom_node
{
	public $nodetype = HDOM_TYPE_TEXT;
	public $tag = 'text';
	public $attr = array();
	public $children = array();
	public $nodes = array();
	public $parent = null;
	public $_ = array();
	public $tag_start = 0;
	private $dom = null;

	function __construct($dom)
	{
		$this->dom = $dom;
		$dom->nodes[] = $this;
	}

	function __destruct()
	{
		$this->clear();
	}

	function __toString()
	{
		return $this->outertext();
	}

	function clear()
	{
		$this->dom = null;
		$this->nodes = null;
		$this->parent = null;
		$this->children = null;
	}

	function dump($show_attr = true, $depth = 0)
	{
		echo str_repeat("\t", $depth) . $this->tag;

		if ($show_attr && count($this->attr) > 0) {
			echo '(';
			foreach ($this->attr as $k => $v) {
				echo "[$k]=>\"$v\", ";
			}
			echo ')';
		}

		echo "\n";

		if ($this->nodes) {
			foreach ($this->nodes as $node) {
				$node->dump($show_attr, $depth + 1);
			}
		}
	}

	function dump_node($echo = true)
	{
		$string = $this->tag;

		if (count($this->attr) > 0) {
			$string .= '(';
			foreach ($this->attr as $k => $v) {
				$string .= "[$k]=>\"$v\", ";
			}
			$string .= ')';
		}

		if (count($this->_) > 0) {
			$string .= ' $_ (';
			foreach ($this->_ as $k => $v) {
				if (is_array($v)) {
					$string .= "[$k]=>(";
					foreach ($v as $k2 => $v2) {
						$string .= "[$k2]=>\"$v2\", ";
					}
					$string .= ')';
				} else {
					$string .= "[$k]=>\"$v\", ";
				}
			}
			$string .= ')';
		}

		if (isset($this->text)) {
			$string .= " text: ({$this->text})";
		}

		$string .= ' HDOM_INNER_INFO: ';

		if (isset($node->_[HDOM_INFO_INNER])) {
			$string .= "'" . $node->_[HDOM_INFO_INNER] . "'";
		} else {
			$string .= ' NULL ';
		}

		$string .= ' children: ' . count($this->children);
		$string .= ' nodes: ' . count($this->nodes);
		$string .= ' tag_start: ' . $this->tag_start;
		$string .= "\n";

		if ($echo) {
			echo $string;
			return;
		} else {
			return $string;
		}
	}

	function parent($parent = null)
	{
		// I am SURE that this doesn't work properly.
		// It fails to unset the current node from it's current parents nodes or
		// children list first.
		if ($parent !== null) {
			$this->parent = $parent;
			$this->parent->nodes[] = $this;
			$this->parent->children[] = $this;
		}

		return $this->parent;
	}

	function has_child()
	{
		return !empty($this->children);
	}

	function children($idx = -1)
	{
		if ($idx === -1) {
			return $this->children;
		}

		if (isset($this->children[$idx])) {
			return $this->children[$idx];
		}

		return null;
	}

	function first_child()
	{
		if (count($this->children) > 0) {
			return $this->children[0];
		}
		return null;
	}

	function last_child()
	{
		if (count($this->children) > 0) {
			return end($this->children);
		}
		return null;
	}

	function next_sibling()
	{
		if ($this->parent === null) {
			return null;
		}

		$idx = array_search($this, $this->parent->children, true);

		if ($idx !== false && isset($this->parent->children[$idx + 1])) {
			return $this->parent->children[$idx + 1];
		}

		return null;
	}

	function prev_sibling()
	{
		if ($this->parent === null) {
			return null;
		}

		$idx = array_search($this, $this->parent->children, true);

		if ($idx !== false && $idx > 0) {
			return $this->parent->children[$idx - 1];
		}

		return null;
	}

	function find_ancestor_tag($tag)
	{
		global $debug_object;
		if (is_object($debug_object)) { $debug_object->debug_log_entry(1); }

		if ($this->parent === null) {
			return null;
		}

		$ancestor = $this->parent;

		while (!is_null($ancestor)) {
			if (is_object($debug_object)) {
				$debug_object->debug_log(2, 'Current tag is: ' . $ancestor->tag);
			}

			if ($ancestor->tag === $tag) {
				break;
			}

			$ancestor = $ancestor->parent;
		}

		return $ancestor;
	}

	function innertext()
	{
		if (isset($this->_[HDOM_INFO_INNER])) {
			return $this->_[HDOM_INFO_INNER];
		}

		if (isset($this->_[HDOM_INFO_TEXT])) {
			return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
		}

		$ret = '';

		foreach ($this->nodes as $n) {
			$ret .= $n->outertext();
		}

		return $ret;
	}

	function outertext()
	{
		global $debug_object;

		if (is_object($debug_object)) {
			$text = '';

			if ($this->tag === 'text') {
				if (!empty($this->text)) {
					$text = ' with text: ' . $this->text;
				}
			}

			$debug_object->debug_log(1, 'Innertext of tag: ' . $this->tag . $text);
		}

		if ($this->tag === 'root') {
			return $this->innertext();
		}

		// todo: What is the use of this callback? Remove?
		if ($this->dom && $this->dom->callback !== null) {
			call_user_func_array($this->dom->callback, array($this));
		}

		if (isset($this->_[HDOM_INFO_OUTER])) {
			return $this->_[HDOM_INFO_OUTER];
		}

		if (isset($this->_[HDOM_INFO_TEXT])) {
			return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
		}

		$ret = '';

		if ($this->dom && $this->dom->nodes[$this->_[HDOM_INFO_BEGIN]]) {
			$ret = $this->dom->nodes[$this->_[HDOM_INFO_BEGIN]]->makeup();
		}

		if (isset($this->_[HDOM_INFO_INNER])) {
			// todo: <br> should either never have HDOM_INFO_INNER or always
			if ($this->tag !== 'br') {
				$ret .= $this->_[HDOM_INFO_INNER];
			}
		} elseif ($this->nodes) {
			foreach ($this->nodes as $n) {
				$ret .= $this->convert_text($n->outertext());
			}
		}

		if (isset($this->_[HDOM_INFO_END]) && $this->_[HDOM_INFO_END] != 0) {
			$ret .= '</' . $this->tag . '>';
		}

		return $ret;
	}

	function text()
	{
		if (isset($this->_[HDOM_INFO_INNER])) {
			return $this->_[HDOM_INFO_INNER];
		}

		switch ($this->nodetype) {
			case HDOM_TYPE_TEXT: return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
			case HDOM_TYPE_COMMENT: return '';
			case HDOM_TYPE_UNKNOWN: return '';
		}

		if (strcasecmp($this->tag, 'script') === 0) { return ''; }
		if (strcasecmp($this->tag, 'style') === 0) { return ''; }

		$ret = '';

		// In rare cases, (always node type 1 or HDOM_TYPE_ELEMENT - observed
		// for some span tags, and some p tags) $this->nodes is set to NULL.
		// NOTE: This indicates that there is a problem where it's set to NULL
		// without a clear happening.
		// WHY is this happening?
		if (!is_null($this->nodes)) {
			foreach ($this->nodes as $n) {
				// Start paragraph after a blank line
				if ($n->tag === 'p') {
					$ret = trim($ret) . "\n\n";
				}

				$ret .= $this->convert_text($n->text());

				// If this node is a span... add a space at the end of it so
				// multiple spans don't run into each other.  This is plaintext
				// after all.
				if ($n->tag === 'span') {
					$ret .= $this->dom->default_span_text;
				}
			}
		}
		return $ret;
	}

	function xmltext()
	{
		$ret = $this->innertext();
		$ret = str_ireplace('<![CDATA[', '', $ret);
		$ret = str_replace(']]>', '', $ret);
		return $ret;
	}

	function makeup()
	{
		// text, comment, unknown
		if (isset($this->_[HDOM_INFO_TEXT])) {
			return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
		}

		$ret = '<' . $this->tag;
		$i = -1;

		foreach ($this->attr as $key => $val) {
			++$i;

			// skip removed attribute
			if ($val === null || $val === false) { continue; }

			$ret .= $this->_[HDOM_INFO_SPACE][$i][0];

			//no value attr: nowrap, checked selected...
			if ($val === true) {
				$ret .= $key;
			} else {
				switch ($this->_[HDOM_INFO_QUOTE][$i])
				{
					case HDOM_QUOTE_DOUBLE: $quote = '"'; break;
					case HDOM_QUOTE_SINGLE: $quote = '\''; break;
					default: $quote = '';
				}

				$ret .= $key
				. $this->_[HDOM_INFO_SPACE][$i][1]
				. '='
				. $this->_[HDOM_INFO_SPACE][$i][2]
				. $quote
				. $val
				. $quote;
			}
		}

		$ret = $this->dom->restore_noise($ret);
		return $ret . $this->_[HDOM_INFO_ENDSPACE] . '>';
	}

	function find($selector, $idx = null, $lowercase = false)
	{
		$selectors = $this->parse_selector($selector);
		if (($count = count($selectors)) === 0) { return array(); }
		$found_keys = array();

		// find each selector
		for ($c = 0; $c < $count; ++$c) {
			// The change on the below line was documented on the sourceforge
			// code tracker id 2788009
			// used to be: if (($levle=count($selectors[0]))===0) return array();
			if (($levle = count($selectors[$c])) === 0) { return array(); }
			if (!isset($this->_[HDOM_INFO_BEGIN])) { return array(); }

			$head = array($this->_[HDOM_INFO_BEGIN] => 1);
			$cmd = ' '; // Combinator

			// handle descendant selectors, no recursive!
			for ($l = 0; $l < $levle; ++$l) {
				$ret = array();

				foreach ($head as $k => $v) {
					$n = ($k === -1) ? $this->dom->root : $this->dom->nodes[$k];
					//PaperG - Pass this optional parameter on to the seek function.
					$n->seek($selectors[$c][$l], $ret, $cmd, $lowercase);
				}

				$head = $ret;
				$cmd = $selectors[$c][$l][4]; // Next Combinator
			}

			foreach ($head as $k => $v) {
				if (!isset($found_keys[$k])) {
					$found_keys[$k] = 1;
				}
			}
		}

		// sort keys
		ksort($found_keys);

		$found = array();
		foreach ($found_keys as $k => $v) {
			$found[] = $this->dom->nodes[$k];
		}

		// return nth-element or array
		if (is_null($idx)) { return $found; }
		elseif ($idx < 0) { $idx = count($found) + $idx; }
		return (isset($found[$idx])) ? $found[$idx] : null;
	}

	protected function seek($selector, &$ret, $parent_cmd, $lowercase = false)
	{
		global $debug_object;
		if (is_object($debug_object)) { $debug_object->debug_log_entry(1); }

		list($tag, $id, $class, $attributes, $cmb) = $selector;
		$nodes = array();

		if ($parent_cmd === ' ') { // Descendant Combinator
			// Find parent closing tag if the current element doesn't have a closing
			// tag (i.e. void element)
			$end = (!empty($this->_[HDOM_INFO_END])) ? $this->_[HDOM_INFO_END] : 0;
			if ($end == 0) {
				$parent = $this->parent;
				while (!isset($parent->_[HDOM_INFO_END]) && $parent !== null) {
					$end -= 1;
					$parent = $parent->parent;
				}
				$end += $parent->_[HDOM_INFO_END];
			}

			// Get list of target nodes
			$nodes_start = $this->_[HDOM_INFO_BEGIN] + 1;
			$nodes_count = $end - $nodes_start;
			$nodes = array_slice($this->dom->nodes, $nodes_start, $nodes_count, true);
		} elseif ($parent_cmd === '>') { // Child Combinator
			$nodes = $this->children;
		} elseif ($parent_cmd === '+'
			&& $this->parent
			&& in_array($this, $this->parent->children)) { // Next-Sibling Combinator
				$index = array_search($this, $this->parent->children, true) + 1;
				if ($index < count($this->parent->children))
					$nodes[] = $this->parent->children[$index];
		} elseif ($parent_cmd === '~'
			&& $this->parent
			&& in_array($this, $this->parent->children)) { // Subsequent Sibling Combinator
				$index = array_search($this, $this->parent->children, true);
				$nodes = array_slice($this->parent->children, $index);
		}

		// Go throgh each element starting at this element until the end tag
		// Note: If this element is a void tag, any previous void element is
		// skipped.
		foreach($nodes as $node) {
			$pass = true;

			// Skip root nodes
			if(!$node->parent) {
				$pass = false;
			}

			// Handle 'text' selector
			if($pass && $tag === 'text' && $node->tag === 'text') {
				$ret[array_search($node, $this->dom->nodes, true)] = 1;
				unset($node);
				continue;
			}

			// Skip if node isn't a child node (i.e. text nodes)
			if($pass && !in_array($node, $node->parent->children, true)) {
				$pass = false;
			}

			// Skip if tag doesn't match
			if ($pass && $tag !== '' && $tag !== $node->tag && $tag !== '*') {
				$pass = false;
			}

			// Skip if ID doesn't exist
			if ($pass && $id !== '' && !isset($node->attr['id'])) {
				$pass = false;
			}

			// Check if ID matches
			if ($pass && $id !== '' && isset($node->attr['id'])) {
				// Note: Only consider the first ID (as browsers do)
				$node_id = explode(' ', trim($node->attr['id']))[0];

				if($id !== $node_id) { $pass = false; }
			}

			// Check if all class(es) exist
			if ($pass && $class !== '' && is_array($class) && !empty($class)) {
				if (isset($node->attr['class'])) {
					$node_classes = explode(' ', $node->attr['class']);

					if ($lowercase) {
						$node_classes = array_map('strtolower', $node_classes);
					}

					foreach($class as $c) {
						if(!in_array($c, $node_classes)) {
							$pass = false;
							break;
						}
					}
				} else {
					$pass = false;
				}
			}

			// Check attributes
			if ($pass
				&& $attributes !== ''
				&& is_array($attributes)
				&& !empty($attributes)) {
					foreach($attributes as $a) {
						list (
							$att_name,
							$att_expr,
							$att_val,
							$att_inv,
							$att_case_sensitivity
						) = $a;

						// Handle indexing attributes (i.e. "[2]")
						/**
						 * Note: This is not supported by the CSS Standard but adds
						 * the ability to select items compatible to XPath (i.e.
						 * the 3rd element within it's parent).
						 *
						 * Note: This doesn't conflict with the CSS Standard which
						 * doesn't work on numeric attributes anyway.
						 */
						if (is_numeric($att_name)
							&& $att_expr === ''
							&& $att_val === '') {
								$count = 0;

								// Find index of current element in parent
								foreach ($node->parent->children as $c) {
									if ($c->tag === $node->tag) ++$count;
									if ($c === $node) break;
								}

								// If this is the correct node, continue with next
								// attribute
								if ($count === (int)$att_name) continue;
						}

						// Check attribute availability
						if ($att_inv) { // Attribute should NOT be set
							if (isset($node->attr[$att_name])) {
								$pass = false;
								break;
							}
						} else { // Attribute should be set
							// todo: "plaintext" is not a valid CSS selector!
							if ($att_name !== 'plaintext'
								&& !isset($node->attr[$att_name])) {
									$pass = false;
									break;
							}
						}

						// Continue with next attribute if expression isn't defined
						if ($att_expr === '') continue;

						// If they have told us that this is a "plaintext"
						// search then we want the plaintext of the node - right?
						// todo "plaintext" is not a valid CSS selector!
						if ($att_name === 'plaintext') {
							$nodeKeyValue = $node->text();
						} else {
							$nodeKeyValue = $node->attr[$att_name];
						}

						if (is_object($debug_object)) {
							$debug_object->debug_log(2,
								'testing node: '
								. $node->tag
								. ' for attribute: '
								. $att_name
								. $att_expr
								. $att_val
								. ' where nodes value is: '
								. $nodeKeyValue
							);
						}

						// If lowercase is set, do a case insensitive test of
						// the value of the selector.
						if ($lowercase) {
							$check = $this->match(
								$att_expr,
								strtolower($att_val),
								strtolower($nodeKeyValue),
								$att_case_sensitivity
							);
						} else {
							$check = $this->match(
								$att_expr,
								$att_val,
								$nodeKeyValue,
								$att_case_sensitivity
							);
						}

						if (is_object($debug_object)) {
							$debug_object->debug_log(2,
								'after match: '
								. ($check ? 'true' : 'false')
							);
						}

						if (!$check) {
							$pass = false;
							break;
						}
					}
			}

			// Found a match. Add to list and clear node
			if ($pass) $ret[$node->_[HDOM_INFO_BEGIN]] = 1;
			unset($node);
		}
		// It's passed by reference so this is actually what this function returns.
		if (is_object($debug_object)) {
			$debug_object->debug_log(1, 'EXIT - ret: ', $ret);
		}
	}

	protected function match($exp, $pattern, $value, $case_sensitivity)
	{
		global $debug_object;
		if (is_object($debug_object)) {$debug_object->debug_log_entry(1);}

		if ($case_sensitivity === 'i') {
			$pattern = strtolower($pattern);
			$value = strtolower($value);
		}

		switch ($exp) {
			case '=':
				return ($value === $pattern);
			case '!=':
				return ($value !== $pattern);
			case '^=':
				return preg_match('/^' . preg_quote($pattern, '/') . '/', $value);
			case '$=':
				return preg_match('/' . preg_quote($pattern, '/') . '$/', $value);
			case '*=':
				return preg_match('/' . preg_quote($pattern, '/') . '/', $value);
			case '|=':
				/**
				 * [att|=val]
				 *
				 * Represents an element with the att attribute, its value
				 * either being exactly "val" or beginning with "val"
				 * immediately followed by "-" (U+002D).
				 */
				return strpos($value, $pattern) === 0;
			case '~=':
				/**
				 * [att~=val]
				 *
				 * Represents an element with the att attribute whose value is a
				 * whitespace-separated list of words, one of which is exactly
				 * "val". If "val" contains whitespace, it will never represent
				 * anything (since the words are separated by spaces). Also if
				 * "val" is the empty string, it will never represent anything.
				 */
				return in_array($pattern, explode(' ', trim($value)), true);
		}
		return false;
	}

	protected function parse_selector($selector_string)
	{
		global $debug_object;
		if (is_object($debug_object)) { $debug_object->debug_log_entry(1); }

		/**
		 * Pattern of CSS selectors, modified from mootools (https://mootools.net/)
		 *
		 * Paperg: Add the colon to the attribute, so that it properly finds
		 * <tag attr:ibute="something" > like google does.
		 *
		 * Note: if you try to look at this attribute, you MUST use getAttribute
		 * since $dom->x:y will fail the php syntax check.
		 *
		 * Notice the \[ starting the attribute? and the @? following? This
		 * implies that an attribute can begin with an @ sign that is not
		 * captured. This implies that an html attribute specifier may start
		 * with an @ sign that is NOT captured by the expression. Farther study
		 * is required to determine of this should be documented or removed.
		 *
		 * Matches selectors in this order:
		 *
		 * [0] - full match
		 *
		 * [1] - tag name
		 *     ([\w:\*-]*)
		 *     Matches the tag name consisting of zero or more words, colons,
		 *     asterisks and hyphens.
		 *
		 * [2] - id name
		 *     (?:\#([\w-]+))
		 *     Optionally matches a id name, consisting of an "#" followed by
		 *     the id name (one or more words and hyphens).
		 *
		 * [3] - class names (including dots)
		 *     (?:\.([\w\.-]+))?
		 *     Optionally matches a list of classs, consisting of an "."
		 *     followed by the class name (one or more words and hyphens)
		 *     where multiple classes can be chained (i.e. ".foo.bar.baz")
		 *
		 * [4] - attributes
		 *     ((?:\[@?(?:!?[\w:-]+)(?:(?:[!*^$|~]?=)[\"']?(?:.*?)[\"']?)?(?:\s*?(?:[iIsS])?)?\])+)?
		 *     Optionally matches the attributes list
		 *
		 * [5] - separator
		 *     ([\/, >+~]+)
		 *     Matches the selector list separator
		 */
		// phpcs:ignore Generic.Files.LineLength
		$pattern = "/([\w:\*-]*)(?:\#([\w-]+))?(?:|\.([\w\.-]+))?((?:\[@?(?:!?[\w:-]+)(?:(?:[!*^$|~]?=)[\"']?(?:.*?)[\"']?)?(?:\s*?(?:[iIsS])?)?\])+)?([\/, >+~]+)/is";

		preg_match_all(
			$pattern,
			trim($selector_string) . ' ', // Add final ' ' as pseudo separator
			$matches,
			PREG_SET_ORDER
		);

		if (is_object($debug_object)) {
			$debug_object->debug_log(2, 'Matches Array: ', $matches);
		}

		$selectors = array();
		$result = array();

		foreach ($matches as $m) {
			$m[0] = trim($m[0]);

			// Skip NoOps
			if ($m[0] === '' || $m[0] === '/' || $m[0] === '//') { continue; }

			// Convert to lowercase
			if ($this->dom->lowercase) {
				$m[1] = strtolower($m[1]);
			}

			// Extract classes
			if ($m[3] !== '') { $m[3] = explode('.', $m[3]); }

			/* Extract attributes (pattern based on the pattern above!)

			 * [0] - full match
			 * [1] - attribute name
			 * [2] - attribute expression
			 * [3] - attribute value
			 * [4] - case sensitivity
			 *
			 * Note: Attributes can be negated with a "!" prefix to their name
			 */
			if($m[4] !== '') {
				preg_match_all(
					"/\[@?(!?[\w:-]+)(?:([!*^$|~]?=)[\"']?(.*?)[\"']?)?(?:\s+?([iIsS])?)?\]/is",
					trim($m[4]),
					$attributes,
					PREG_SET_ORDER
				);

				// Replace element by array
				$m[4] = array();

				foreach($attributes as $att) {
					// Skip empty matches
					if(trim($att[0]) === '') { continue; }

					$inverted = (isset($att[1][0]) && $att[1][0] === '!');
					$m[4][] = array(
						$inverted ? substr($att[1], 1) : $att[1], // Name
						(isset($att[2])) ? $att[2] : '', // Expression
						(isset($att[3])) ? $att[3] : '', // Value
						$inverted, // Inverted Flag
						(isset($att[4])) ? strtolower($att[4]) : '', // Case-Sensitivity
					);
				}
			}

			// Sanitize Separator
			if ($m[5] !== '' && trim($m[5]) === '') { // Descendant Separator
				$m[5] = ' ';
			} else { // Other Separator
				$m[5] = trim($m[5]);
			}

			// Clear Separator if it's a Selector List
			if ($is_list = ($m[5] === ',')) { $m[5] = ''; }

			// Remove full match before adding to results
			array_shift($m);
			$result[] = $m;

			if ($is_list) { // Selector List
				$selectors[] = $result;
				$result = array();
			}
		}

		if (count($result) > 0) { $selectors[] = $result; }
		return $selectors;
	}

	function __get($name)
	{
		if (isset($this->attr[$name])) {
			return $this->convert_text($this->attr[$name]);
		}
		switch ($name) {
			case 'outertext': return $this->outertext();
			case 'innertext': return $this->innertext();
			case 'plaintext': return $this->text();
			case 'xmltext': return $this->xmltext();
			default: return array_key_exists($name, $this->attr);
		}
	}

	function __set($name, $value)
	{
		global $debug_object;
		if (is_object($debug_object)) { $debug_object->debug_log_entry(1); }

		switch ($name) {
			case 'outertext': return $this->_[HDOM_INFO_OUTER] = $value;
			case 'innertext':
				if (isset($this->_[HDOM_INFO_TEXT])) {
					return $this->_[HDOM_INFO_TEXT] = $value;
				}
				return $this->_[HDOM_INFO_INNER] = $value;
		}

		if (!isset($this->attr[$name])) {
			$this->_[HDOM_INFO_SPACE][] = array(' ', '', '');
			$this->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
		}

		$this->attr[$name] = $value;
	}

	function __isset($name)
	{
		switch ($name) {
			case 'outertext': return true;
			case 'innertext': return true;
			case 'plaintext': return true;
		}
		//no value attr: nowrap, checked selected...
		return (array_key_exists($name, $this->attr)) ? true : isset($this->attr[$name]);
	}

	function __unset($name)
	{
		if (isset($this->attr[$name])) { unset($this->attr[$name]); }
	}

	function convert_text($text)
	{
		global $debug_object;
		if (is_object($debug_object)) { $debug_object->debug_log_entry(1); }

		$converted_text = $text;

		$sourceCharset = '';
		$targetCharset = '';

		if ($this->dom) {
			$sourceCharset = strtoupper($this->dom->_charset);
			$targetCharset = strtoupper($this->dom->_target_charset);
		}

		if (is_object($debug_object)) {
			$debug_object->debug_log(3,
				'source charset: '
				. $sourceCharset
				. ' target charaset: '
				. $targetCharset
			);
		}

		if (!empty($sourceCharset)
			&& !empty($targetCharset)
			&& (strcasecmp($sourceCharset, $targetCharset) != 0)) {
			// Check if the reported encoding could have been incorrect and the text is actually already UTF-8
			if ((strcasecmp($targetCharset, 'UTF-8') == 0)
				&& ($this->is_utf8($text))) {
				$converted_text = $text;
			} else {
				$converted_text = iconv($sourceCharset, $targetCharset, $text);
			}
		}

		// Lets make sure that we don't have that silly BOM issue with any of the utf-8 text we output.
		if ($targetCharset === 'UTF-8') {
			if (substr($converted_text, 0, 3) === "\xef\xbb\xbf") {
				$converted_text = substr($converted_text, 3);
			}

			if (substr($converted_text, -3) === "\xef\xbb\xbf") {
				$converted_text = substr($converted_text, 0, -3);
			}
		}

		return $converted_text;
	}

	static function is_utf8($str)
	{
		$c = 0; $b = 0;
		$bits = 0;
		$len = strlen($str);
		for($i = 0; $i < $len; $i++) {
			$c = ord($str[$i]);
			if($c > 128) {
				if(($c >= 254)) { return false; }
				elseif($c >= 252) { $bits = 6; }
				elseif($c >= 248) { $bits = 5; }
				elseif($c >= 240) { $bits = 4; }
				elseif($c >= 224) { $bits = 3; }
				elseif($c >= 192) { $bits = 2; }
				else { return false; }
				if(($i + $bits) > $len) { return false; }
				while($bits > 1) {
					$i++;
					$b = ord($str[$i]);
					if($b < 128 || $b > 191) { return false; }
					$bits--;
				}
			}
		}
		return true;
	}

	function get_display_size()
	{
		global $debug_object;

		$width = -1;
		$height = -1;

		if ($this->tag !== 'img') {
			return false;
		}

		// See if there is aheight or width attribute in the tag itself.
		if (isset($this->attr['width'])) {
			$width = $this->attr['width'];
		}

		if (isset($this->attr['height'])) {
			$height = $this->attr['height'];
		}

		// Now look for an inline style.
		if (isset($this->attr['style'])) {
			// Thanks to user gnarf from stackoverflow for this regular expression.
			$attributes = array();

			preg_match_all(
				'/([\w-]+)\s*:\s*([^;]+)\s*;?/',
				$this->attr['style'],
				$matches,
				PREG_SET_ORDER
			);

			foreach ($matches as $match) {
				$attributes[$match[1]] = $match[2];
			}

			// If there is a width in the style attributes:
			if (isset($attributes['width']) && $width == -1) {
				// check that the last two characters are px (pixels)
				if (strtolower(substr($attributes['width'], -2)) === 'px') {
					$proposed_width = substr($attributes['width'], 0, -2);
					// Now make sure that it's an integer and not something stupid.
					if (filter_var($proposed_width, FILTER_VALIDATE_INT)) {
						$width = $proposed_width;
					}
				}
			}

			// If there is a width in the style attributes:
			if (isset($attributes['height']) && $height == -1) {
				// check that the last two characters are px (pixels)
				if (strtolower(substr($attributes['height'], -2)) == 'px') {
					$proposed_height = substr($attributes['height'], 0, -2);
					// Now make sure that it's an integer and not something stupid.
					if (filter_var($proposed_height, FILTER_VALIDATE_INT)) {
						$height = $proposed_height;
					}
				}
			}

		}

		// Future enhancement:
		// Look in the tag to see if there is a class or id specified that has
		// a height or width attribute to it.

		// Far future enhancement
		// Look at all the parent tags of this image to see if they specify a
		// class or id that has an img selector that specifies a height or width
		// Note that in this case, the class or id will have the img subselector
		// for it to apply to the image.

		// ridiculously far future development
		// If the class or id is specified in a SEPARATE css file thats not on
		// the page, go get it and do what we were just doing for the ones on
		// the page.

		$result = array(
			'height' => $height,
			'width' => $width
		);

		return $result;
	}

	function save($filepath = '')
	{
		$ret = $this->outertext();

		if ($filepath !== '') {
			file_put_contents($filepath, $ret, LOCK_EX);
		}

		return $ret;
	}

	function addClass($class)
	{
		if (is_string($class)) {
			$class = explode(' ', $class);
		}

		if (is_array($class)) {
			foreach($class as $c) {
				if (isset($this->class)) {
					if ($this->hasClass($c)) {
						continue;
					} else {
						$this->class .= ' ' . $c;
					}
				} else {
					$this->class = $c;
				}
			}
		} else {
			if (is_object($debug_object)) {
				$debug_object->debug_log(2, 'Invalid type: ', gettype($class));
			}
		}
	}

	function hasClass($class)
	{
		if (is_string($class)) {
			if (isset($this->class)) {
				return in_array($class, explode(' ', $this->class), true);
			}
		} else {
			if (is_object($debug_object)) {
				$debug_object->debug_log(2, 'Invalid type: ', gettype($class));
			}
		}

		return false;
	}

	function removeClass($class = null)
	{
		if (!isset($this->class)) {
			return;
		}

		if (is_null($class)) {
			$this->removeAttribute('class');
			return;
		}

		if (is_string($class)) {
			$class = explode(' ', $class);
		}

		if (is_array($class)) {
			$class = array_diff(explode(' ', $this->class), $class);
			if (empty($class)) {
				$this->removeAttribute('class');
			} else {
				$this->class = implode(' ', $class);
			}
		}
	}

	function getAllAttributes()
	{
		return $this->attr;
	}

	function getAttribute($name)
	{
		return $this->__get($name);
	}

	function setAttribute($name, $value)
	{
		$this->__set($name, $value);
	}

	function hasAttribute($name)
	{
		return $this->__isset($name);
	}

	function removeAttribute($name)
	{
		$this->__set($name, null);
	}

	function remove()
	{
		if ($this->parent) {
			$this->parent->removeChild($this);
		}
	}

	function removeChild($node)
	{
		$nidx = array_search($node, $this->nodes, true);
		$cidx = array_search($node, $this->children, true);
		$didx = array_search($node, $this->dom->nodes, true);

		if ($nidx !== false && $cidx !== false && $didx !== false) {

			foreach($node->children as $child) {
				$node->removeChild($child);
			}

			foreach($node->nodes as $entity) {
				$enidx = array_search($entity, $node->nodes, true);
				$edidx = array_search($entity, $node->dom->nodes, true);

				if ($enidx !== false && $edidx !== false) {
					unset($node->nodes[$enidx]);
					unset($node->dom->nodes[$edidx]);
				}
			}

			unset($this->nodes[$nidx]);
			unset($this->children[$cidx]);
			unset($this->dom->nodes[$didx]);

			$node->clear();

		}
	}

	function getElementById($id)
	{
		return $this->find("#$id", 0);
	}

	function getElementsById($id, $idx = null)
	{
		return $this->find("#$id", $idx);
	}

	function getElementByTagName($name)
	{
		return $this->find($name, 0);
	}

	function getElementsByTagName($name, $idx = null)
	{
		return $this->find($name, $idx);
	}

	function parentNode()
	{
		return $this->parent();
	}

	function childNodes($idx = -1)
	{
		return $this->children($idx);
	}

	function firstChild()
	{
		return $this->first_child();
	}

	function lastChild()
	{
		return $this->last_child();
	}

	function nextSibling()
	{
		return $this->next_sibling();
	}

	function previousSibling()
	{
		return $this->prev_sibling();
	}

	function hasChildNodes()
	{
		return $this->has_child();
	}

	function nodeName()
	{
		return $this->tag;
	}

	function appendChild($node)
	{
		$node->parent($this);
		return $node;
	}

}

class simple_html_dom
{
	public $root = null;
	public $nodes = array();
	public $callback = null;
	public $lowercase = false;
	public $original_size;
	public $size;

	protected $pos;
	protected $doc;
	protected $char;

	protected $cursor;
	protected $parent;
	protected $noise = array();
	protected $token_blank = " \t\r\n";
	protected $token_equal = ' =/>';
	protected $token_slash = " />\r\n\t";
	protected $token_attr = ' >';

	public $_charset = '';
	public $_target_charset = '';

	protected $default_br_text = '';

	public $default_span_text = '';

	protected $self_closing_tags = array(
		'area' => 1,
		'base' => 1,
		'br' => 1,
		'col' => 1,
		'embed' => 1,
		'hr' => 1,
		'img' => 1,
		'input' => 1,
		'link' => 1,
		'meta' => 1,
		'param' => 1,
		'source' => 1,
		'track' => 1,
		'wbr' => 1
	);
	protected $block_tags = array(
		'body' => 1,
		'div' => 1,
		'form' => 1,
		'root' => 1,
		'span' => 1,
		'table' => 1
	);
	protected $optional_closing_tags = array(
		// Not optional, see
		// https://www.w3.org/TR/html/textlevel-semantics.html#the-b-element
		'b' => array('b' => 1),
		'dd' => array('dd' => 1, 'dt' => 1),
		// Not optional, see
		// https://www.w3.org/TR/html/grouping-content.html#the-dl-element
		'dl' => array('dd' => 1, 'dt' => 1),
		'dt' => array('dd' => 1, 'dt' => 1),
		'li' => array('li' => 1),
		'optgroup' => array('optgroup' => 1, 'option' => 1),
		'option' => array('optgroup' => 1, 'option' => 1),
		'p' => array('p' => 1),
		'rp' => array('rp' => 1, 'rt' => 1),
		'rt' => array('rp' => 1, 'rt' => 1),
		'td' => array('td' => 1, 'th' => 1),
		'th' => array('td' => 1, 'th' => 1),
		'tr' => array('td' => 1, 'th' => 1, 'tr' => 1),
	);

	function __construct(
		$str = null,
		$lowercase = true,
		$forceTagsClosed = true,
		$target_charset = DEFAULT_TARGET_CHARSET,
		$stripRN = true,
		$defaultBRText = DEFAULT_BR_TEXT,
		$defaultSpanText = DEFAULT_SPAN_TEXT,
		$options = 0)
	{
		if ($str) {
			if (preg_match('/^http:\/\//i', $str) || is_file($str)) {
				$this->load_file($str);
			} else {
				$this->load(
					$str,
					$lowercase,
					$stripRN,
					$defaultBRText,
					$defaultSpanText,
					$options
				);
			}
		}
		// Forcing tags to be closed implies that we don't trust the html, but
		// it can lead to parsing errors if we SHOULD trust the html.
		if (!$forceTagsClosed) {
			$this->optional_closing_array = array();
		}

		$this->_target_charset = $target_charset;
	}

	function __destruct()
	{
		$this->clear();
	}

	function load(
		$str,
		$lowercase = true,
		$stripRN = true,
		$defaultBRText = DEFAULT_BR_TEXT,
		$defaultSpanText = DEFAULT_SPAN_TEXT,
		$options = 0)
	{
		global $debug_object;

		// prepare
		$this->prepare($str, $lowercase, $defaultBRText, $defaultSpanText);

		// Per sourceforge http://sourceforge.net/tracker/?func=detail&aid=2949097&group_id=218559&atid=1044037
		// Script tags removal now preceeds style tag removal.
		// strip out <script> tags
		$this->remove_noise("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is");
		$this->remove_noise("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is");

		// strip out the \r \n's if we are told to.
		if ($stripRN) {
			$this->doc = str_replace("\r", ' ', $this->doc);
			$this->doc = str_replace("\n", ' ', $this->doc);

			// set the length of content since we have changed it.
			$this->size = strlen($this->doc);
		}

		// strip out cdata
		$this->remove_noise("'<!\[CDATA\[(.*?)\]\]>'is", true);
		// strip out comments
		$this->remove_noise("'<!--(.*?)-->'is");
		// strip out <style> tags
		$this->remove_noise("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is");
		$this->remove_noise("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is");
		// strip out preformatted tags
		$this->remove_noise("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is");
		// strip out server side scripts
		$this->remove_noise("'(<\?)(.*?)(\?>)'s", true);

		if($options & HDOM_SMARTY_AS_TEXT) { // Strip Smarty scripts
			$this->remove_noise("'(\{\w)(.*?)(\})'s", true);
		}

		// parsing
		$this->parse();
		// end
		$this->root->_[HDOM_INFO_END] = $this->cursor;
		$this->parse_charset();

		// make load function chainable
		return $this;
	}

	function load_file()
	{
		$args = func_get_args();

		if(($doc = call_user_func_array('file_get_contents', $args)) !== false) {
			$this->load($doc, true);
		} else {
			return false;
		}
	}

	function set_callback($function_name)
	{
		$this->callback = $function_name;
	}

	function remove_callback()
	{
		$this->callback = null;
	}

	function save($filepath = '')
	{
		$ret = $this->root->innertext();
		if ($filepath !== '') { file_put_contents($filepath, $ret, LOCK_EX); }
		return $ret;
	}

	function find($selector, $idx = null, $lowercase = false)
	{
		return $this->root->find($selector, $idx, $lowercase);
	}

	function clear()
	{
		if (isset($this->nodes)) {
			foreach ($this->nodes as $n) {
				$n->clear();
				$n = null;
			}
		}

		// This add next line is documented in the sourceforge repository.
		// 2977248 as a fix for ongoing memory leaks that occur even with the
		// use of clear.
		if (isset($this->children)) {
			foreach ($this->children as $n) {
				$n->clear();
				$n = null;
			}
		}

		if (isset($this->parent)) {
			$this->parent->clear();
			unset($this->parent);
		}

		if (isset($this->root)) {
			$this->root->clear();
			unset($this->root);
		}

		unset($this->doc);
		unset($this->noise);
	}

	function dump($show_attr = true)
	{
		$this->root->dump($show_attr);
	}

	protected function prepare(
		$str, $lowercase = true,
		$defaultBRText = DEFAULT_BR_TEXT,
		$defaultSpanText = DEFAULT_SPAN_TEXT)
	{
		$this->clear();

		$this->doc = trim($str);
		$this->size = strlen($this->doc);
		$this->original_size = $this->size; // original size of the html
		$this->pos = 0;
		$this->cursor = 1;
		$this->noise = array();
		$this->nodes = array();
		$this->lowercase = $lowercase;
		$this->default_br_text = $defaultBRText;
		$this->default_span_text = $defaultSpanText;
		$this->root = new simple_html_dom_node($this);
		$this->root->tag = 'root';
		$this->root->_[HDOM_INFO_BEGIN] = -1;
		$this->root->nodetype = HDOM_TYPE_ROOT;
		$this->parent = $this->root;
		if ($this->size > 0) { $this->char = $this->doc[0]; }
	}

	protected function parse()
	{
		while (true) {
			// Read next tag if there is no text between current position and the
			// next opening tag.
			if (($s = $this->copy_until_char('<')) === '') {
				if($this->read_tag()) {
					continue;
				} else {
					return true;
				}
			}

			// Add a text node for text between tags
			$node = new simple_html_dom_node($this);
			++$this->cursor;
			$node->_[HDOM_INFO_TEXT] = $s;
			$this->link_nodes($node, false);
		}
	}

	protected function parse_charset()
	{
		global $debug_object;

		$charset = null;

		if (function_exists('get_last_retrieve_url_contents_content_type')) {
			$contentTypeHeader = get_last_retrieve_url_contents_content_type();
			$success = preg_match('/charset=(.+)/', $contentTypeHeader, $matches);
			if ($success) {
				$charset = $matches[1];
				if (is_object($debug_object)) {
					$debug_object->debug_log(2,
						'header content-type found charset of: '
						. $charset
					);
				}
			}
		}

		if (empty($charset)) {
			// https://www.w3.org/TR/html/document-metadata.html#statedef-http-equiv-content-type
			$el = $this->root->find('meta[http-equiv=Content-Type]', 0, true);

			if (!empty($el)) {
				$fullvalue = $el->content;
				if (is_object($debug_object)) {
					$debug_object->debug_log(2,
						'meta content-type tag found'
						. $fullvalue
					);
				}

				if (!empty($fullvalue)) {
					$success = preg_match(
						'/charset=(.+)/i',
						$fullvalue,
						$matches
					);

					if ($success) {
						$charset = $matches[1];
					} else {
						// If there is a meta tag, and they don't specify the
						// character set, research says that it's typically
						// ISO-8859-1
						if (is_object($debug_object)) {
							$debug_object->debug_log(2,
								'meta content-type tag couldn\'t be parsed. using iso-8859 default.'
							);
						}

						$charset = 'ISO-8859-1';
					}
				}
			}
		}

		if (empty($charset)) {
			// https://www.w3.org/TR/html/document-metadata.html#character-encoding-declaration
			if ($meta = $this->root->find('meta[charset]', 0)) {
				$charset = $meta->charset;
				if (is_object($debug_object)) {
					$debug_object->debug_log(2, 'meta charset: ' . $charset);
				}
			}
		}

		if (empty($charset)) {
			// Try to guess the charset based on the content
			// Requires Multibyte String (mbstring) support (optional)
			if (function_exists('mb_detect_encoding')) {
				/**
				 * mb_detect_encoding() is not intended to distinguish between
				 * charsets, especially single-byte charsets. Its primary
				 * purpose is to detect which multibyte encoding is in use,
				 * i.e. UTF-8, UTF-16, shift-JIS, etc.
				 *
				 * -- https://bugs.php.net/bug.php?id=38138
				 *
				 * Adding both CP1251/ISO-8859-5 and CP1252/ISO-8859-1 will
				 * always result in CP1251/ISO-8859-5 and vice versa.
				 *
				 * Thus, only detect if it's either UTF-8 or CP1252/ISO-8859-1
				 * to stay compatible.
				 */
				$encoding = mb_detect_encoding(
					$this->doc,
					array( 'UTF-8', 'CP1252', 'ISO-8859-1' )
				);

				if ($encoding === 'CP1252' || $encoding === 'ISO-8859-1') {
					// Due to a limitation of mb_detect_encoding
					// 'CP1251'/'ISO-8859-5' will be detected as
					// 'CP1252'/'ISO-8859-1'. This will cause iconv to fail, in
					// which case we can simply assume it is the other charset.
					if (!@iconv('CP1252', 'UTF-8', $this->doc)) {
						$encoding = 'CP1251';
					}
				}

				if ($encoding !== false) {
					$charset = $encoding;
					if (is_object($debug_object)) {
						$debug_object->debug_log(2, 'mb_detect: ' . $charset);
					}
				}
			}
		}

		if (empty($charset)) {
			// Assume it's UTF-8 as it is the most likely charset to be used
			$charset = 'UTF-8';
			if (is_object($debug_object)) {
				$debug_object->debug_log(2, 'No match found, assume ' . $charset);
			}
		}

		// Since CP1252 is a superset, if we get one of it's subsets, we want
		// it instead.
		if ((strtolower($charset) == 'iso-8859-1')
			|| (strtolower($charset) == 'latin1')
			|| (strtolower($charset) == 'latin-1')) {
			$charset = 'CP1252';
			if (is_object($debug_object)) {
				$debug_object->debug_log(2,
					'replacing ' . $charset . ' with CP1252 as its a superset'
				);
			}
		}

		if (is_object($debug_object)) {
			$debug_object->debug_log(1, 'EXIT - ' . $charset);
		}

		return $this->_charset = $charset;
	}

	protected function read_tag()
	{
		// Set end position if no further tags found
		if ($this->char !== '<') {
			$this->root->_[HDOM_INFO_END] = $this->cursor;
			return false;
		}

		$begin_tag_pos = $this->pos;
		$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next

		// end tag
		if ($this->char === '/') {
			$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next

			// Skip whitespace in end tags (i.e. in "</   html>")
			$this->skip($this->token_blank);
			$tag = $this->copy_until_char('>');

			// Skip attributes in end tags
			if (($pos = strpos($tag, ' ')) !== false) {
				$tag = substr($tag, 0, $pos);
			}

			$parent_lower = strtolower($this->parent->tag);
			$tag_lower = strtolower($tag);

			// The end tag is supposed to close the parent tag. Handle situations
			// when it doesn't
			if ($parent_lower !== $tag_lower) {
				// Parent tag does not have to be closed necessarily (optional closing tag)
				// Current tag is a block tag, so it may close an ancestor
				if (isset($this->optional_closing_tags[$parent_lower])
					&& isset($this->block_tags[$tag_lower])) {

					$this->parent->_[HDOM_INFO_END] = 0;
					$org_parent = $this->parent;

					// Traverse ancestors to find a matching opening tag
					// Stop at root node
					while (($this->parent->parent)
						&& strtolower($this->parent->tag) !== $tag_lower
					){
						$this->parent = $this->parent->parent;
					}

					// If we don't have a match add current tag as text node
					if (strtolower($this->parent->tag) !== $tag_lower) {
						$this->parent = $org_parent; // restore origonal parent

						if ($this->parent->parent) {
							$this->parent = $this->parent->parent;
						}

						$this->parent->_[HDOM_INFO_END] = $this->cursor;
						return $this->as_text_node($tag);
					}
				} elseif (($this->parent->parent)
					&& isset($this->block_tags[$tag_lower])
				) {
					// Grandparent exists and current tag is a block tag, so our
					// parent doesn't have an end tag
					$this->parent->_[HDOM_INFO_END] = 0; // No end tag
					$org_parent = $this->parent;

					// Traverse ancestors to find a matching opening tag
					// Stop at root node
					while (($this->parent->parent)
						&& strtolower($this->parent->tag) !== $tag_lower
					) {
						$this->parent = $this->parent->parent;
					}

					// If we don't have a match add current tag as text node
					if (strtolower($this->parent->tag) !== $tag_lower) {
						$this->parent = $org_parent; // restore origonal parent
						$this->parent->_[HDOM_INFO_END] = $this->cursor;
						return $this->as_text_node($tag);
					}
				} elseif (($this->parent->parent)
					&& strtolower($this->parent->parent->tag) === $tag_lower
				) { // Grandparent exists and current tag closes it
					$this->parent->_[HDOM_INFO_END] = 0;
					$this->parent = $this->parent->parent;
				} else { // Random tag, add as text node
					return $this->as_text_node($tag);
				}
			}

			// Set end position of parent tag to current cursor position
			$this->parent->_[HDOM_INFO_END] = $this->cursor;

			if ($this->parent->parent) {
				$this->parent = $this->parent->parent;
			}

			$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
			return true;
		}

		// start tag
		$node = new simple_html_dom_node($this);
		$node->_[HDOM_INFO_BEGIN] = $this->cursor;
		++$this->cursor;
		$tag = $this->copy_until($this->token_slash); // Get tag name
		$node->tag_start = $begin_tag_pos;

		// doctype, cdata & comments...
		// <!DOCTYPE html>
		// <![CDATA[ ... ]]>
		// <!-- Comment -->
		if (isset($tag[0]) && $tag[0] === '!') {
			$node->_[HDOM_INFO_TEXT] = '<' . $tag . $this->copy_until_char('>');

			if (isset($tag[2]) && $tag[1] === '-' && $tag[2] === '-') { // Comment ("<!--")
				$node->nodetype = HDOM_TYPE_COMMENT;
				$node->tag = 'comment';
			} else { // Could be doctype or CDATA but we don't care
				$node->nodetype = HDOM_TYPE_UNKNOWN;
				$node->tag = 'unknown';
			}

			if ($this->char === '>') { $node->_[HDOM_INFO_TEXT] .= '>'; }

			$this->link_nodes($node, true);
			$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
			return true;
		}

		// The start tag cannot contain another start tag, if so add as text
		// i.e. "<<html>"
		if ($pos = strpos($tag, '<') !== false) {
			$tag = '<' . substr($tag, 0, -1);
			$node->_[HDOM_INFO_TEXT] = $tag;
			$this->link_nodes($node, false);
			$this->char = $this->doc[--$this->pos]; // prev
			return true;
		}

		// Handle invalid tag names (i.e. "<html#doc>")
		if (!preg_match('/^\w[\w:-]*$/', $tag)) {
			$node->_[HDOM_INFO_TEXT] = '<' . $tag . $this->copy_until('<>');

			// Next char is the beginning of a new tag, don't touch it.
			if ($this->char === '<') {
				$this->link_nodes($node, false);
				return true;
			}

			// Next char closes current tag, add and be done with it.
			if ($this->char === '>') { $node->_[HDOM_INFO_TEXT] .= '>'; }
			$this->link_nodes($node, false);
			$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
			return true;
		}

		// begin tag, add new node
		$node->nodetype = HDOM_TYPE_ELEMENT;
		$tag_lower = strtolower($tag);
		$node->tag = ($this->lowercase) ? $tag_lower : $tag;

		// handle optional closing tags
		if (isset($this->optional_closing_tags[$tag_lower])) {
			// Traverse ancestors to close all optional closing tags
			while (isset($this->optional_closing_tags[$tag_lower][strtolower($this->parent->tag)])) {
				$this->parent->_[HDOM_INFO_END] = 0;
				$this->parent = $this->parent->parent;
			}
			$node->parent = $this->parent;
		}

		$guard = 0; // prevent infinity loop

		// [0] Space between tag and first attribute
		$space = array($this->copy_skip($this->token_blank), '', '');

		// attributes
		do {
			// Everything until the first equal sign should be the attribute name
			$name = $this->copy_until($this->token_equal);

			if ($name === '' && $this->char !== null && $space[0] === '') {
				break;
			}

			if ($guard === $this->pos) { // Escape infinite loop
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				continue;
			}

			$guard = $this->pos;

			// handle endless '<'
			// Out of bounds before the tag ended
			if ($this->pos >= $this->size - 1 && $this->char !== '>') {
				$node->nodetype = HDOM_TYPE_TEXT;
				$node->_[HDOM_INFO_END] = 0;
				$node->_[HDOM_INFO_TEXT] = '<' . $tag . $space[0] . $name;
				$node->tag = 'text';
				$this->link_nodes($node, false);
				return true;
			}

			// handle mismatch '<'
			// Attributes cannot start after opening tag
			if ($this->doc[$this->pos - 1] == '<') {
				$node->nodetype = HDOM_TYPE_TEXT;
				$node->tag = 'text';
				$node->attr = array();
				$node->_[HDOM_INFO_END] = 0;
				$node->_[HDOM_INFO_TEXT] = substr(
					$this->doc,
					$begin_tag_pos,
					$this->pos - $begin_tag_pos - 1
				);
				$this->pos -= 2;
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				$this->link_nodes($node, false);
				return true;
			}

			if ($name !== '/' && $name !== '') { // this is a attribute name
				// [1] Whitespace after attribute name
				$space[1] = $this->copy_skip($this->token_blank);

				$name = $this->restore_noise($name); // might be a noisy name

				if ($this->lowercase) { $name = strtolower($name); }

				if ($this->char === '=') { // attribute with value
					$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
					$this->parse_attr($node, $name, $space); // get attribute value
				} else {
					//no value attr: nowrap, checked selected...
					$node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
					$node->attr[$name] = true;
					if ($this->char != '>') { $this->char = $this->doc[--$this->pos]; } // prev
				}

				$node->_[HDOM_INFO_SPACE][] = $space;

				// prepare for next attribute
				$space = array(
					$this->copy_skip($this->token_blank),
					'',
					''
				);
			} else { // no more attributes
				break;
			}
		} while ($this->char !== '>' && $this->char !== '/'); // go until the tag ended

		$this->link_nodes($node, true);
		$node->_[HDOM_INFO_ENDSPACE] = $space[0];

		// handle empty tags (i.e. "<div/>")
		if ($this->copy_until_char('>') === '/') {
			$node->_[HDOM_INFO_ENDSPACE] .= '/';
			$node->_[HDOM_INFO_END] = 0;
		} else {
			// reset parent
			if (!isset($this->self_closing_tags[strtolower($node->tag)])) {
				$this->parent = $node;
			}
		}

		$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next

		// If it's a BR tag, we need to set it's text to the default text.
		// This way when we see it in plaintext, we can generate formatting that the user wants.
		// since a br tag never has sub nodes, this works well.
		if ($node->tag === 'br') {
			$node->_[HDOM_INFO_INNER] = $this->default_br_text;
		}

		return true;
	}

	protected function parse_attr($node, $name, &$space)
	{
		$is_duplicate = isset($node->attr[$name]);

		if (!$is_duplicate) // Copy whitespace between "=" and value
			$space[2] = $this->copy_skip($this->token_blank);

		switch ($this->char) {
			case '"':
				$quote_type = HDOM_QUOTE_DOUBLE;
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				$value = $this->copy_until_char('"');
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				break;
			case '\'':
				$quote_type = HDOM_QUOTE_SINGLE;
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				$value = $this->copy_until_char('\'');
				$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
				break;
			default:
				$quote_type = HDOM_QUOTE_NO;
				$value = $this->copy_until($this->token_attr);
		}

		$value = $this->restore_noise($value);

		// PaperG: Attributes should not have \r or \n in them, that counts as
		// html whitespace.
		$value = str_replace("\r", '', $value);
		$value = str_replace("\n", '', $value);

		// PaperG: If this is a "class" selector, lets get rid of the preceeding
		// and trailing space since some people leave it in the multi class case.
		if ($name === 'class') {
			$value = trim($value);
		}

		if (!$is_duplicate) {
			$node->_[HDOM_INFO_QUOTE][] = $quote_type;
			$node->attr[$name] = $value;
		}
	}

	protected function link_nodes(&$node, $is_child)
	{
		$node->parent = $this->parent;
		$this->parent->nodes[] = $node;
		if ($is_child) {
			$this->parent->children[] = $node;
		}
	}

	protected function as_text_node($tag)
	{
		$node = new simple_html_dom_node($this);
		++$this->cursor;
		$node->_[HDOM_INFO_TEXT] = '</' . $tag . '>';
		$this->link_nodes($node, false);
		$this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
		return true;
	}

	protected function skip($chars)
	{
		$this->pos += strspn($this->doc, $chars, $this->pos);
		$this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
	}

	protected function copy_skip($chars)
	{
		$pos = $this->pos;
		$len = strspn($this->doc, $chars, $pos);
		$this->pos += $len;
		$this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
		if ($len === 0) { return ''; }
		return substr($this->doc, $pos, $len);
	}

	protected function copy_until($chars)
	{
		$pos = $this->pos;
		$len = strcspn($this->doc, $chars, $pos);
		$this->pos += $len;
		$this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
		return substr($this->doc, $pos, $len);
	}

	protected function copy_until_char($char)
	{
		if ($this->char === null) { return ''; }

		if (($pos = strpos($this->doc, $char, $this->pos)) === false) {
			$ret = substr($this->doc, $this->pos, $this->size - $this->pos);
			$this->char = null;
			$this->pos = $this->size;
			return $ret;
		}

		if ($pos === $this->pos) { return ''; }

		$pos_old = $this->pos;
		$this->char = $this->doc[$pos];
		$this->pos = $pos;
		return substr($this->doc, $pos_old, $pos - $pos_old);
	}

	protected function remove_noise($pattern, $remove_tag = false)
	{
		global $debug_object;
		if (is_object($debug_object)) { $debug_object->debug_log_entry(1); }

		$count = preg_match_all(
			$pattern,
			$this->doc,
			$matches,
			PREG_SET_ORDER | PREG_OFFSET_CAPTURE
		);

		for ($i = $count - 1; $i > -1; --$i) {
			$key = '___noise___' . sprintf('% 5d', count($this->noise) + 1000);

			if (is_object($debug_object)) {
				$debug_object->debug_log(2, 'key is: ' . $key);
			}

			$idx = ($remove_tag) ? 0 : 1; // 0 = entire match, 1 = submatch
			$this->noise[$key] = $matches[$i][$idx][0];
			$this->doc = substr_replace($this->doc, $key, $matches[$i][$idx][1], strlen($matches[$i][$idx][0]));
		}

		// reset the length of content
		$this->size = strlen($this->doc);

		if ($this->size > 0) {
			$this->char = $this->doc[0];
		}
	}

	function restore_noise($text)
	{
		global $debug_object;
		if (is_object($debug_object)) { $debug_object->debug_log_entry(1); }

		while (($pos = strpos($text, '___noise___')) !== false) {
			// Sometimes there is a broken piece of markup, and we don't GET the
			// pos+11 etc... token which indicates a problem outside of us...

			// todo: "___noise___1000" (or any number with four or more digits)
			// in the DOM causes an infinite loop which could be utilized by
			// malicious software
			if (strlen($text) > $pos + 15) {
				$key = '___noise___'
				. $text[$pos + 11]
				. $text[$pos + 12]
				. $text[$pos + 13]
				. $text[$pos + 14]
				. $text[$pos + 15];

				if (is_object($debug_object)) {
					$debug_object->debug_log(2, 'located key of: ' . $key);
				}

				if (isset($this->noise[$key])) {
					$text = substr($text, 0, $pos)
					. $this->noise[$key]
					. substr($text, $pos + 16);
				} else {
					// do this to prevent an infinite loop.
					$text = substr($text, 0, $pos)
					. 'UNDEFINED NOISE FOR KEY: '
					. $key
					. substr($text, $pos + 16);
				}
			} else {
				// There is no valid key being given back to us... We must get
				// rid of the ___noise___ or we will have a problem.
				$text = substr($text, 0, $pos)
				. 'NO NUMERIC NOISE KEY'
				. substr($text, $pos + 11);
			}
		}
		return $text;
	}

	function search_noise($text)
	{
		global $debug_object;
		if (is_object($debug_object)) { $debug_object->debug_log_entry(1); }

		foreach($this->noise as $noiseElement) {
			if (strpos($noiseElement, $text) !== false) {
				return $noiseElement;
			}
		}
	}

	function __toString()
	{
		return $this->root->innertext();
	}

	function __get($name)
	{
		switch ($name) {
			case 'outertext':
				return $this->root->innertext();
			case 'innertext':
				return $this->root->innertext();
			case 'plaintext':
				return $this->root->text();
			case 'charset':
				return $this->_charset;
			case 'target_charset':
				return $this->_target_charset;
		}
	}

	function childNodes($idx = -1)
	{
		return $this->root->childNodes($idx);
	}

	function firstChild()
	{
		return $this->root->first_child();
	}

	function lastChild()
	{
		return $this->root->last_child();
	}

	function createElement($name, $value = null)
	{
		return @str_get_html("<$name>$value</$name>")->firstChild();
	}

	function createTextNode($value)
	{
		return @end(str_get_html($value)->nodes);
	}

	function getElementById($id)
	{
		return $this->find("#$id", 0);
	}

	function getElementsById($id, $idx = null)
	{
		return $this->find("#$id", $idx);
	}

	function getElementByTagName($name)
	{
		return $this->find($name, 0);
	}

	function getElementsByTagName($name, $idx = -1)
	{
		return $this->find($name, $idx);
	}

	function loadFile()
	{
		$args = func_get_args();
		$this->load_file($args);
	}
}

$html1=file_get_html('https://www.worldometers.info/coronavirus/country/morocco/');
$data6=$html1->find('div.content-inner',0)->outertext;


$html2=file_get_html('https://www.worldometers.info/coronavirus/country/morocco/');
$data8=$html2->find('div.col-md-12',0)->outertext;




$html=file_get_html('https://sehhty.com/en/ma-covid/');

$data=$html->find('div#vac',0)->innertext;
$data1=$html->find('.govTable',1)->innertext;
$data2=$html->find('div#daily_cases',0)->innertext;
$data3=$html->find('div.card',0)->outertext;
$data4=$html->find('div.card',1)->outertext;
$data5=$html->find('div.card',2)->outertext;

$data7=$html->find('div#contrystats',0)->outertext;


$arr=array();

for($i=0;$i<24;$i++){
    if ($i%2 !=0){
        $arr[$i]=$html->find('div.cardlabel',$i)->plaintext;

    }
}

$arr1=array();

for($i=0;$i<12;$i++){

        $arr1[$i]=$html->find('div.cardcases',$i)->plaintext;


}



?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrapfin.min.css">
    <link rel="stylesheet" href="assets/css/thems.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/app1.css">
    <title>MACOVID-19</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <style>
        .label-counter a{
                display: none;
        }

        #page-top{
            display: none;
        }
        #maincounter-wrap a{
            display: none;
        }

    .number-table {
        font-size: 20px;
        font-weight: bold
    }

    .number-table-main {
        font-size: 24px;
        font-weight: bold
    }

:root {
  --blue: #00BEC1;
}


.title {
  color: var(--blue);
  text-align: center;
}

.country_options {
  margin-left: 50%;
  transform: translate(-50%);
  height: 35px;
  -webkit-appearance: none;
  border: 2px solid var(--blue);
}


th{position:sticky;top:0px;background:orangered;}*,*::before,*::after{box-sizing:border-box}h6{margin-top:0;margin-bottom:0.5rem}strong{font-weight:bolder}a{color:#4768ad;text-decoration:none;background-color:transparent}a:hover{color:#0148ae;text-decoration:none}.col-lg-4 img{vertical-align:middle;border-style:none;border-radius:3px;border:1px solid #b9b7b7;width:22px;height:14px}table{border-collapse:collapse}th{text-align:inherit}h6{margin-bottom:0.5rem;font-weight:900;line-height:1.25;color:#001737}h6{font-size:0.875rem}.col-md-12,.col-lg-4{position:relative;width:100%;padding-right:15px;padding-left:15px}@media (min-width: 768px){.col-md-12{flex:0 0 100%;max-width:100%}}@media (min-width: 992px){.col-lg-4{flex:0 0 33.33333%;max-width:99.33333%}}.col-lg-4 .table{width:100%;margin-bottom:1rem;color:#5C5D60}.col-lg-4 .table th,.table td{padding:0.75rem;vertical-align:top;border-top:1px solid rgba(72, 94, 144, 0.16)}.col-lg-4 .table thead th{vertical-align:bottom;border-bottom:1px solid #E1E5EE;border-top:1px solid #E1E5EE}.col-lg-4 .table .thead-light th{color:#747474;background-color:#F7F8FA;border-color:#E1E5EE;padding:15px 10px;font-weight:700}.card{position:relative;display:flex;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:1px solid rgba(72, 94, 144, 0.16);border-radius:0.25rem}.card-header{padding:0.75rem 1.25rem;margin-bottom:0;background-color:rgba(0, 0, 0, 0.03);border-bottom:1px solid rgba(72, 94, 144, 0.16)}.card-header:first-child{border-radius:calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0}.progress{display:flex;height:1rem;overflow:hidden;font-size:0.65625rem;background-color:#e3e7ed;border-radius:0.25rem}.progress-bar{display:flex;flex-direction:column;justify-content:center;color:#fff;text-align:center;white-space:nowrap;background-color:#4768ad;transition:width 0.6s ease}@media (prefers-reduced-motion: reduce){.progress-bar{transition:none}}.text-left{text-align:left!important}.text-right{text-align:right!important}.text-primary{color:#4768ad!important}@media print{*,*::before,*::after{text-shadow:none!important;box-shadow:none!important}a:not(.btn){text-decoration:underline}thead{display:table-header-group}tr,img{page-break-inside:avoid}.table{border-collapse:collapse!important}.table td,.table th{background-color:#fff!important}}strong{font-weight:600}.card-header{background-color:transparent;border-color:rgba(72, 94, 144, 0.16)}.card-header{padding:15px}@media (min-width: 576px){.card-header{padding:15px 20px}}.row-sm>div{padding-left:10px;padding-right:10px}.progress{height:10px}.col-lg-4 .table th,.table td{padding:8px 10px;line-height:1.5}.col-lg-4 .table thead th{font-weight:500}.col-lg-4 .table thead th{border-bottom-width:1px}.col-lg-4 .thead-light{color:#fff}.col-lg-4 .thead-light th{border-top-width:0}.col-lg-4 .thead-light + tbody tr:first-child td{border-top-width:0}.col-lg-4 .thead-light{background-color:rgba(72, 94, 144, 0.16);color:#596882}.ht-5{height:5px}.mg-0{margin:0px}.mg-r-5{margin-left:5px}.mg-t-20{margin-top:20px}.pd-x-10{padding-left:10px;padding-right:10px}.caseColor1{color:#6800ff}.caseColor2{color:#1ec598}.caseColor3{color:#e85347}.caseColor5{color:#64b1bd}.caseBg1{background-color:#6800ff!important}.caseBg2{background-color:#1ec598!important}.caseBg3{background-color:#e85347!important}.caseBg5{background-color:#64b1bd!important}.table.covid-data{margin:0}.table.covid-data td{padding:10px 10px;font-size:12px}.percentage-data{display:flex;align-items:center;justify-content:flex-end}.percentage-data .progress{width:80px;border-radius:0;margin-left:10px}.percentage-data strong{min-width:42px}.mg-t-20{display:inline-block}.comparecontent{max-height:450px;overflow:auto}.comparecontent::-webkit-scrollbar{width:5px}.comparecontent::-webkit-scrollbar-thumb{border-radius:10px;background:-webkit-gradient(linear,left top,left bottom,from(#664ca2),to(#4f1ba2));box-shadow:inset 2px 2px 2px rgba(255,255,255,.25), inset -2px -2px 2px rgba(0,0,0,.25)}.comparecontent::-webkit-scrollbar-track{background-color:#fff;background:linear-gradient(to right,#c5c5c5,#bfbebf 1px,#d7d6da 1px,#c5c3c3)}


    .box {
    border: 1px solid #868282;
    margin: 3px;
    margin-bottom: 3px;
    border-radius: 5px 5px 0 0;
    background: rgba(252,185,0,1);
    display: inline-block;
    color: #fff;
    text-align: center;
    width: 250px;
    box-sizing: border-box;
    align-items: center;
    }


    .box3 {
    max-width: 45%;
}
div{

    font-size: 100%;
    vertical-align: baseline;
}
#table{
    color:#fff;
    background: #64b1bd;
    width: 100%;
    text-align: center;
    padding: 0;
    text-indent: initial;

    border-spacing: 2px;
    border: 2px;
    font-size: 100%;
    vertical-align: baseline;
}

    .govTable {
    min-width: 45%;
    margin: 0 auto;
    display: inline-block;
    direction: rtl;}
    .boxo{
    width: 100%;
    margin-left: 0;
    margin-right: 0;
    }
    .v1{

        width: 100%;
    }
    #sectiono {
            margin-top: 100px;
            width: 100%;
            height: 100%;


        }

            .boxo{
                width: 100%;

            }
            .bamo{
                width: 30%;
                margin-left: 35px;
                float: left;



            }
            #more{
                display: none;
            }

            #ajo{
                color:#001737;
            }
            #ajo h2{
                color:#00BEC1;
                margin: 10px;
                margin-bottom: 10px;
            }


</style>
<!--

TemplateMo 548 Training Studio

https://templatemo.com/tm-548-training-studio

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/templatemo-training-studio.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">


    </head>

    <body>

    <!-- ***** Preloader Start ***** -->

    <!-- ***** Preloader End ***** -->


    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo">MA<em> Covid</em></a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="#features">About</a></li>
                            <li class="scroll-to-section"><a href="#our-classes">Map</a></li>
                            <li class="scroll-to-section"><a href="#chart">Charts</a></li>
                            <li class="scroll-to-section"><a href="#contact-us">Contact</a></li>
                            <li class="main-button"><a href="#">Sign Up</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner" id="top">
        <img src="assets\images\imgcovid.jpg" id="bg-video">
            <source src="assets/images/gym-video.mp4" type="video/mp4" />
        </video>
        <div class="video-overlay header-text">
            <div class="caption">
                <h6>We Can Beat It Together</h6>
                <h2>Health monitoring in <em>Morocco</em></h2>
                <div class="main-button scroll-to-section">
                    <a href="{{url('untitled')}}" target="_blank" >Personal Risks</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->

    <!-- ***** Features Item Start ***** -->
    <section class="section" id="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>What You need to <em>Know</em></h2>
                        <img src="assets/images/line-dec.png" alt="waves">
                        <p>The MACOVID is a dynamic system for regional and personal COVID-19 risk assessment based on the most recent data available.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="features-items">
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="First One">
                            </div>
                            <div class="right-content">
                                <h4>National Guidelines</h4>
                                <p>Any of the hundreds of evidence-based clinical guidelines for best practice. there is a guidelines for managing the risk of spread
                                    of the Covid-19 pandemic in the workplace
                                     </p>
                                <a href="https://www.lavieeco.com/documents/guide_relative_mesures_sanitaires.pdf" class="text-button" class="text-button" target="_blank" >Discover More</a>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="second one">
                            </div>
                            <div class="right-content">
                                <h4>World updates regarding COVID-19</h4>
                                <p>There is no one perfect statistic to compare the outbreaks different countries have experienced during this pandemic.Looking at a variety of metrics gives you a more complete view of the virus’ toll on each country.</p>
                                <a href="https://edition.cnn.com/interactive/2020/health/coronavirus-maps-and-cases/" class="text-button" target="_blank">Discover More</a>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="third ">
                            </div>
                            <div class="right-content">
                                <h4> WHO Guidelines</h4>
                                <p>The World Health Organization (WHO) is building a better future for people everywhere. Health lays the foundation for vibrant and productive communities, stronger economies, safer nations and a better world</p>
                                <a href="https://www.who.int/teams/health-product-and-policy-standards/standards-and-specifications/norms-and-standards-for-pharmaceuticals/guidelines" class="text-button" target="_blank">Discover More</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <ul class="features-items">
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="Fourth">
                            </div>
                            <div class="right-content">
                                <h4>Last 24 hours update</h4>
                                <p>Here are updates related to Covid-19 in the last 24 hours</p>
                                <a href="https://www.worldometers.info/coronavirus/country/morocco/" class="text-button" target="_blank">Discover More</a>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="fifth">
                            </div>
                            <div class="right-content">
                                <h4>COVID-19 Morocco
                                    (Total Data)</h4>
                                <p>The disease has spread to every continent and case numbers continue to rise.</p>
                                    </p>
                                <a href="http://sehati.gov.ma/" class="text-button" target="_blank">Discover More</a>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="six">
                            </div>
                            <div class="right-content">
                                <h4> Total Data via a Map</h4>
                                <p> This Data is collected from multiple sources that update at different times. Some places may not provide complete information.</p>
                                <a href="https://infographics.channelnewsasia.com/covid-19/map.html" class="text-button"  target="_blank">Discover More</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Item End ***** -->

    <!-- ***** Call to Action Start ***** -->
    <section class="section" id="call-to-action">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <h2>CHANGE <em>START </em> FROM<em>YOU</em>!</h2>
                        <p>Certain measures will be needed to reduce the risk of exposure and spread of COVID-19(PHYSICAL DISTANCING,FACE COVERINGS,HAND HYGIENE..).</p>
                        <div class="main-button scroll-to-section">
                            <a href="https://www.usm.edu/student-health-services/covid-19-health-protocols.php" target="_blank">Read more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section id="sectiono" class="alert" >




    <!-- ***** Call to Action End ***** -->
    <div class="container rounded    mt-5 mb-5 ">
<div class="row">

    <div class="col-lg-12">

        <div class="bs-component">
   <div class="page-header ">

           <div class="alert alert-warning  text-dark">

        <h4 class="alert-heading"></h4>
        <p class="mb-0 ">

        <b>Statistics of people who were vaccinated in the years 2020/2021/2022</b></p>
        </div>

               <div class="alert   text-dark">

            <div class="v1" style="background-color:white;color:black;text-align: center;">
            <?php echo $data ;?>

        </div>
    </div>
               </div>


               </div></div></div></div>



               <div class="container rounded    mt-5 mb-5 ">
<div class="row">

    <div class="col-lg-12">

        <div class="bs-component">
   <div class="page-header ">

           <div class="alert alert-warning  text-dark">

        <h4 class="alert-heading"></h4>
        <p class="mb-0 ">

        <b>Statistics of people who were vaccinated in the years 2020/2021/2022</b></p>
        </div>

               <div class="alert   text-dark">

            <div class="v1" style="background-color:white;color:black;text-align: center;">
            <?php echo $data6 ;?>

        </div>
        <div>
        <i class="bi bi-clipboard2-heart"></i>
        <big><strong><i style="width: 40px;height: 40px;" class="bi bi-heart-pulse"></i></strong></big>
        </div>
    </div>

               </div>


               </div></div></div></div>




    <!-- ***** Our Classes Start ***** -->
    <section class="section" id="our-classes">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Regional <em>Risks</em></h2>

                    </div>
                </div>
            </div>
            <div class="row" id="tabs">
              <div class="col-lg-4">
                <ul>
                    <li><table id="table"><?php echo $data1 ;?> </table></li>

                </ul>
              </div>
              <div class="col-lg-8">
                <section class='tabs-content'>
                  <article id='tabs-0'>
                  <!-- <div id="map">

                   </div> -->
                   <div class="container rounded ">
<div class="row">


        <div id="popup6" class="alert alert-info col-lg-4" style="display: none;">
            <?php

                echo ''.$arr[1]."<br/>".  $arr1[0] .'cas ';
                ?>
            </div>



        <div id="popup4" class="alert alert-info col-lg-4" style="display: none;">
            <?php

                echo ''.$arr[3]."<br/>".  $arr1[1] .'cas ';
                ?>
            </div>


        <div id="popup7" class="alert alert-info col-lg-4" style="display: none;">
            <?php

                echo ''.$arr[5]."<br/>".  $arr1[2] .'cas ';
                ?>
            </div>


        <div id="popup1" class="alert alert-info col-lg-4" style="display: none;">
            <?php

                echo ''.$arr[7]."<br/>".  $arr1[3] .'cas ';
                ?>
            </div>


        <div id="popup9" class="alert alert-info col-lg-4 " style="display: none;">
            <?php

                echo ''.$arr[9]."<br/>".  $arr1[4] .'cas ';
                ?>
            </div>


        <div id="popup2" class="alert alert-info col-lg-4" style="display: none;">
            <?php

                echo ''.$arr[11]."<br/>".  $arr1[5] .'cas ';
                ?>
            </div>


        <div id="popup3" class="alert alert-info col-lg-6" style="display: none;">
            <?php

                echo ''.$arr[13]."<br/>".  $arr1[6] .'cas ';
                ?>
            </div>


        <div id="popup5" class="alert alert-info col-lg-6" style="display: none;">
            <?php

                echo ''.$arr[15]."<br/>".  $arr1[7] .'cas ';
                ?>
            </div>


        <div id="popup8" class="alert alert-info col-lg-4 mt-10"  style="display: none;">
            <?php

                echo ''.$arr[17]."<br/>".  $arr1[8] .'cas ';
                ?>
            </div>


        <div id="popup11" class="alert alert-info col-lg-4 " style="display: none;">
            <?php

                echo ''.$arr[19]."<br/>".  $arr1[9] .'cas ';
                ?>
            </div>


        <div id="popup10" class="alert alert-info col-lg-4" style="display: none;">
            <?php

                echo ''.$arr[21]."<br/>".  $arr1[10] .'cas ';
                ?>
            </div>


        <div id="popup12" class="alert alert-info col-lg-4" style="display: none;">
            <?php

                echo ''.$arr[23]."<br/>".  $arr1[11] .'cas ';
                ?>
            </div>







    <div class="col-lg-12">

        <div class="bs-component">
                   <div class="map map__image" id="map">


            <svg width="100%" height="100%" viewBox="0 0 600 600" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;">
                <g transform="matrix(1,0,0,1,0,-150.166)">
                    <a id="bh1" xlink:title="Tanger-Tétouan-Al Hoceïma" > <path d="M505.091,182.589L504.892,186.859L506.041,198.086L506.195,198.152L502.255,200.168L495.946,200.872L491.886,205.353L488.042,202.629L484.853,207.196L480.646,208.863L469.044,205.177L466.305,200.334L461.938,203.157L455.035,203.297L452.987,210.197L453.268,214.479L447.364,217.28L444.398,214.698L444.195,211.412L440.184,207.794L431.143,203.926L431.007,196.757L424.616,195.599L422.689,193.654L411.977,192.597L418.39,173.967L420.41,170.051L424.166,155.313L428.854,155.229L431.125,157.004L434.659,154.417L439.282,154.501L441.866,151.162L445.108,150.166L449.411,151.162L447.848,155.252L448.236,160.122L450.504,166.518L453,167.294L457.149,173.011L462.344,177.222L471.697,182.512L484.015,185.115L500.469,179.842L505.091,182.589Z"/>
                    </a>
                    <a id="bh2" xlink:title="L'Oriental" > <path d="M531.531,341.948L526.256,342.012L525.506,340.089L531.379,336.585L528.32,334.86L526.167,327.685L527.414,322.496L522.088,322.895L514.66,320.173L506.68,319.698L505.547,311.562L500.222,308.356L501.128,304.346L497.765,301.387L501.509,296.345L502.669,292.225L509.196,284.92L513.063,279.298L525.129,279.699L534.43,278.549L536.678,276.136L541.826,275.618L536.533,267.937L531.24,262.231L526.994,261.671L526.072,258.819L534.838,250.793L534.521,248.786L522.692,247.158L520.925,243.899L516.857,242.319L511.136,249.383L505.743,253.721L507.51,258.379L504.428,262.328L502.344,258.649L500.032,260.868L494.549,258.649L494.606,253.038L498.971,251.201L500.105,246.48L504.683,237.349L505.181,230.332L501.191,226.787L504.5,223.948L504.183,218.045L507.719,216.567L507.537,211.311L511.525,209.338L513.505,203.219L513.197,199.808L506.432,198.254L506.041,198.086L504.892,186.859L505.091,182.589L508.797,178.616L517.093,182.77L523.41,183.208L532.626,178.978L535.409,174.188L538.721,180.152L538.679,183.518L542.953,187.241L552.317,188.232L557.662,185.154L565.806,188.09L565.675,189.806L582.934,203.267L580.289,208.888L583.548,214.39L582.213,220.062L585.147,223.835L585.122,227.832L587.301,233.125L585.346,243.933L587.103,252.506L589.563,259.431L586.701,266.263L586.422,270.59L590.066,276.757L594.421,281.585L591.297,285.403L597.449,295.244L601.682,297.217L612,305.153L607.348,309.864L604.396,310.349L602.74,319.238L605.155,321.342L604.157,324.638L588.479,323.913L582.99,324.099L572.767,321.628L569.431,325.888L564.717,325.802L556.162,322.821L550.505,325.888L544.704,325.206L540.788,328.016L542.747,337.79L538.033,341.44L531.531,341.948Z" />
                    </a>
                    <a id="bh3" xlink:title="Fès- Meknès" > <path d="M497.765,301.387L495.633,299.827L492.856,302.339L488.552,302.339L486.523,296.195L479.359,288.59L472.218,289.074L468.633,286.308L469.103,283.149L465.74,279.456L459.494,280.04L456.503,286.494L453.186,285.979L452.479,286.43L450.738,288.815L450.656,288.813L450.781,288.675L450.291,287.063L447.408,283.385L448.496,278.102L443.928,278.232L440.013,272.605L440.556,268.979L434.465,266.322L428.663,262.339L428.675,262.258L431.431,258.839L430.444,256.075L430.676,255.191L433.171,249.02L430.397,245.242L433.497,240.48L427.624,237.543L425.085,233.586L428.983,228.743L432.867,230.37L433.226,233.976L438.229,234.63L443.232,231.622L443.452,226.647L440.107,222.847L445.817,220.322L447.364,217.28L453.268,214.479L452.987,210.197L455.035,203.297L461.938,203.157L466.305,200.334L469.044,205.177L480.646,208.863L484.853,207.196L488.042,202.629L491.886,205.353L495.946,200.872L502.255,200.168L506.195,198.152L506.432,198.254L513.197,199.808L513.505,203.219L511.525,209.338L507.537,211.311L507.719,216.567L504.183,218.045L504.5,223.948L501.191,226.787L505.181,230.332L504.683,237.349L500.105,246.48L498.971,251.201L494.606,253.038L494.549,258.649L500.032,260.868L502.344,258.649L504.428,262.328L507.51,258.379L505.743,253.721L511.136,249.383L516.857,242.319L520.925,243.899L522.692,247.158L534.521,248.786L534.838,250.793L526.072,258.819L526.994,261.671L531.24,262.231L536.533,267.937L541.826,275.618L536.678,276.136L534.43,278.549L525.129,279.699L513.063,279.298L509.196,284.92L502.669,292.225L501.509,296.345L497.765,301.387Z"/>
                    </a>
                    <a id="bh4" xlink:title="Rabat-Salé-Kénitra"> <path d="M447.364,217.28L445.817,220.322L440.107,222.847L443.452,226.647L443.232,231.622L438.229,234.63L433.226,233.976L432.867,230.37L428.983,228.743L425.085,233.586L427.624,237.543L433.497,240.48L430.397,245.242L433.171,249.02L430.676,255.191L430.444,256.075L431.431,258.839L428.675,262.258L428.53,263.21L430.202,269.187L428.609,270.862L413.593,267.792L412.433,262.864L408.734,263.037L405.616,270.383L408.3,274.007L401.772,277.887L398.469,273.72L391.025,272.981L391.042,272.972L391.983,266.841L391.187,262.345L388.666,256.995L388.264,257.063L383.862,258.017L384.106,254.337L380.786,246.845L378.581,245.792L384.137,242.166L391.555,233.934L400.93,217.479L407.3,204.466L411.977,192.597L422.689,193.654L424.616,195.599L431.007,196.757L431.143,203.926L440.184,207.794L444.195,211.412L444.398,214.698L447.364,217.28Z" />
                    </a>
                    <a id="bh5" xlink:title="Béni-Mellal-Khénifra" > <path d="M450.781,288.675L449.91,289.641L448.441,291.188L448.605,300.324L447.245,305.012L444.473,308.605L440.524,308.238L438.878,311.557L441.844,313.987L439.777,318.275L434.61,318.787L433.142,323.519L427.567,324.062L426.317,328.531L429.09,332.805L426.785,336.235L423.45,336.32L421.13,340.564L417.068,343.109L410.38,344.126L405.685,348.786L398.795,350.986L393.977,350.227L392.835,357.574L384.568,358.25L382.069,355.667L378.84,356.813L377.58,354.056L379.798,349.04L375.51,347.169L374.649,344.296L371.227,342.167L373.852,339.292L376.752,340.48L383.278,339.801L384.873,336.065L379.994,327.531L379.802,315.284L379.847,314.338L383.673,305.742L383.922,301.686L381.841,296.748L384.22,285.314L386.532,284.024L387.823,274.898L390.179,273.451L391.025,272.981L398.469,273.72L401.772,277.887L408.3,274.007L405.616,270.383L408.734,263.037L412.433,262.864L413.593,267.792L428.609,270.862L430.202,269.187L428.53,263.21L428.663,262.339L434.465,266.322L440.556,268.979L440.013,272.605L443.928,278.232L448.496,278.102L447.408,283.385L450.291,287.063L450.781,288.675Z" />
                    </a>
                    <a id="bh6" xlink:title="Casablanca-Settat"  > <path d="M378.581,245.792L380.786,246.845L384.106,254.337L383.862,258.017L388.264,257.063L388.666,256.995L391.187,262.345L391.983,266.841L391.042,272.972L390.179,273.451L387.823,274.898L386.532,284.024L384.22,285.314L381.841,296.748L383.922,301.686L383.673,305.742L379.847,314.338L379.825,314.79L372.788,311.808L370.931,308.763L367.758,307.801L361.063,309.147L354.86,299.259L350.354,299.215L346.563,291.857L344.042,293.109L342.918,302.819L337.982,308.285L332.472,310.32L330.98,313.813L325.464,314.868L321.766,305.294L311.613,306.406L307.843,302.983L309.221,298.099L305.02,295.919L315.39,285.831L321.21,278.445L321.336,276.219L325.579,271.675L330.225,270.982L334.002,266.848L340.541,264.741L357.948,256.354L363.535,255.127L374.698,246.96L378.581,245.792Z" />
                    </a>
                    <a id="bh7" xlink:title="Marrakech-Safi" > <path d="M379.825,314.79L379.802,315.284L379.994,327.531L384.873,336.065L383.278,339.801L376.752,340.48L373.852,339.292L371.227,342.167L374.649,344.296L375.51,347.169L379.798,349.04L377.58,354.056L372.733,359.288L366.7,360.04L357.017,365.255L355.602,365.853L349.027,370.714L345.965,377.862L341.578,374.487L332.641,379.911L324.349,377.801L316.461,380.699L315.575,373.908L308.345,377.659L305.72,380.383L300.747,380.092L299.874,383.061L294.03,380.231L289.806,381.128L287.056,379.866L282.878,382.904L278.8,382.746L279.06,378.522L275.974,375.065L275.138,368.249L276.757,360.729L275.466,354.552L281.042,346.191L282.481,340.97L293.634,327.43L297.243,320.593L297.899,315.165L296.173,311.887L298.396,307.096L296.322,304.087L305.02,295.919L309.221,298.099L307.843,302.983L311.613,306.406L321.766,305.294L325.464,314.868L330.98,313.813L332.472,310.32L337.982,308.285L342.918,302.819L344.042,293.109L346.563,291.857L350.354,299.215L354.86,299.259L361.063,309.147L367.758,307.801L370.931,308.763L372.788,311.808L379.825,314.79Z" />
                    </a>
                    <a id="bh8" xlink:title="Drâa-Tafilalet" > <path d="M497.765,301.387L501.128,304.346L500.222,308.356L505.547,311.562L506.68,319.698L514.66,320.173L522.088,322.895L527.414,322.496L526.167,327.685L528.32,334.86L531.379,336.585L525.506,340.089L526.256,342.012L531.531,341.948L531.586,344.746L522.231,344.683L516.575,350.083L512.877,351.796L509.942,350.137L508.715,354.861L505.161,357.566L505.161,364.235L514.157,373.467L503.961,385.417L502.862,389.543L492.149,391.208L485.495,393.693L480.295,398.858L477.335,398.902L473.029,403.215L463.625,408.957L455.117,416.612L452.888,416.729L447.34,422.537L446.308,425.406L440.706,430.392L437.552,437.922L437.116,441.279L430.481,438.979L429.503,436.118L425.913,438.481L418.19,439.974L413.079,440.036L404.106,436.003L404.837,429.32L402.389,427.14L404.292,424.021L402.661,420.433L407.171,416.491L398.854,406.206L402.502,401.418L400.576,398.673L396.092,398.793L395.251,394.88L387.32,401.024L381.471,402.35L375.649,399.457L373.61,404.813L368.852,408.597L361.822,405.092L359.697,400.189L363.231,390.612L360.785,388.726L360.921,385.107L355.891,384.793L359.969,379.596L355.618,379.28L356.026,372.814L354.53,368.709L356.241,365.583L357.017,365.255L366.7,360.04L372.733,359.288L377.58,354.056L378.84,356.813L382.069,355.667L384.568,358.25L392.835,357.574L393.977,350.227L398.795,350.986L405.685,348.786L410.38,344.126L417.068,343.109L421.13,340.564L423.45,336.32L426.785,336.235L429.09,332.805L426.317,328.531L427.567,324.062L433.142,323.519L434.61,318.787L439.777,318.275L441.844,313.987L438.878,311.557L440.524,308.238L444.473,308.605L447.245,305.012L448.605,300.324L448.441,291.188L449.91,289.641L450.656,288.813L450.738,288.815L452.479,286.43L453.186,285.979L456.503,286.494L459.494,280.04L465.74,279.456L469.103,283.149L468.633,286.308L472.218,289.074L479.359,288.59L486.523,296.195L488.552,302.339L492.856,302.339L495.633,299.827L497.765,301.387Z"/>
                    </a>
                    <a id="bh9" xlink:title="Souss-Massa" > <path d="M404.106,436.003L401.175,437.828L394.086,438.482L391.941,440.381L386.938,438.922L377.749,437.742L368.371,442.459L360.609,444.112L358.851,446.967L353.498,449.347L346.876,454.799L331.838,465.192L328.831,468.423L319.884,472.823L319.895,489.832L317.971,489.56L309.677,479.253L309.54,475.247L304.782,474.322L304.51,471.083L301.383,469.386L302.878,464.29L297.44,457.949L302.471,456.402L304.238,450.205L301.111,447.26L300.431,443.069L297.848,438.72L292.138,438.098L289.418,441.36L285.883,439.341L282.756,442.292L280.581,439.808L276.23,443.225L273.783,441.205L272.967,435.609L269.432,438.098L267.975,434.039L269.784,431.039L275.529,425.376L280.757,414.649L282.894,408.696L284.429,399.333L280.429,393.078L277.392,390.131L273.35,389.225L276.535,381.081L275.974,375.065L279.06,378.522L278.8,382.746L282.878,382.904L287.056,379.866L289.806,381.128L294.03,380.231L299.874,383.061L300.747,380.092L305.72,380.383L308.345,377.659L315.575,373.908L316.461,380.699L324.349,377.801L332.641,379.911L341.578,374.487L345.965,377.862L349.027,370.714L355.602,365.853L356.241,365.583L354.53,368.709L356.026,372.814L355.618,379.28L359.969,379.596L355.891,384.793L360.921,385.107L360.785,388.726L363.231,390.612L359.697,400.189L361.822,405.092L368.852,408.597L373.61,404.813L375.649,399.457L381.471,402.35L387.32,401.024L395.251,394.88L396.092,398.793L400.576,398.673L402.502,401.418L398.854,406.206L407.171,416.491L402.661,420.433L404.292,424.021L402.389,427.14L404.837,429.32L404.106,436.003Z" />
                    </a>
                    <a id="bh10" xlink:title="Guelmim-Oued Noun"  > <path d="M267.975,434.039L269.432,438.098L272.967,435.609L273.783,441.205L276.23,443.225L280.581,439.808L282.756,442.292L285.883,439.341L289.418,441.36L292.138,438.098L297.848,438.72L300.431,443.069L301.111,447.26L304.238,450.205L302.471,456.402L297.44,457.949L302.878,464.29L301.383,469.386L304.51,471.083L304.782,474.322L309.54,475.247L309.677,479.253L317.971,489.56L319.895,489.832L319.917,517.687L319.687,548.31L308.136,545.751L296.806,526.738L292.274,529.277L285.929,529.786L280.037,527.5L273.239,518.348L267.121,520.893L260.096,513L254.884,511.982L248.993,514.529L246.953,512.746L227.465,513L225.426,515.293L219.081,512.236L211.453,512.178L203.841,500.49L201.509,494.307L213.427,489.022L219.405,480.692L229.299,470.819L243.574,463.309L249.711,459.191L254.208,453.108L259.53,448.162L266.197,438.58L267.975,434.039Z" />
                    </a>
                    <a id="bh11" xlink:title="Laâyoune-Sakia El Hamra"  > <path d="M201.509,494.307L203.841,500.49L211.453,512.178L219.081,512.236L225.426,515.293L227.465,513L246.953,512.746L248.993,514.529L254.884,511.982L260.096,513L267.121,520.893L273.239,518.348L280.037,527.5L285.929,529.786L292.274,529.277L296.806,526.738L308.136,545.751L319.687,548.31L319.639,588.706L285.783,588.605L192.799,588.664L192.708,639.494L192.823,651.653L185.861,649.866L180.649,650.608L172.038,653.58L157.083,648.628L148.245,649.37L139.181,648.132L131.703,653.58L110.855,648.132L107.683,650.361L101.792,652.343L99.299,650.608L94.54,653.828L91.821,651.847L90.461,647.636L88.195,646.148L83.664,647.141L82.239,643.817L85.117,634.463L85.498,628.193L84.535,622.022L86.767,613.034L90.556,606.564L92.587,598.145L95.148,595.025L97.868,588.765L98.693,580.983L101.391,577.522L107.433,574.364L109.793,570.676L117.613,568.725L129.051,561.312L133.432,557.06L138.839,542.966L139.283,539.241L142.542,533.891L148.489,517.13L152.161,514.891L155.515,508.356L158.012,505.557L167.461,504.377L190.984,499.38L201.509,494.307Z" />
                    </a>
                    <a id="bh12" xlink:title="Dakhla-Oued Ed-Dahab"  > <path d="M82.239,643.817L83.664,647.141L88.195,646.148L90.461,647.636L91.821,651.847L94.54,653.828L99.299,650.608L101.792,652.343L107.683,650.361L110.855,648.132L131.703,653.58L139.181,648.132L148.245,649.37L157.083,648.628L172.038,653.58L180.649,650.608L185.861,649.866L192.823,651.653L192.947,694.781L188.127,695.494L179.29,700.158L170.452,701.631L154.363,712.659L149.851,721.183L149.302,724.558L154.787,781.783L108.279,781.313L45.258,781.36L4.778,781.954L2.363,790.975L0,792L1.45,780.603L3.164,772.464L3.164,765.989L9.831,747.878L14.613,742.422L16.337,743.514L21.724,741.25L24.274,733.101L26.866,730.834L27.48,724.838L29.596,717.841L32.411,716.881L35.183,712.128L33.659,709.772L44.135,690.236L50.22,681.435L51.616,676.673L49.572,674.312L56.082,670.386L56.822,668.299L66.282,659.371L72.303,651.907L77.827,649.42L79.212,645.937L82.239,643.817Z" />
                    </a>
                </g>
            </svg>

    </div>

</div>
</div></div></div>

                    <h4> Why using Maps?</h4>
                    <p>Some may think maps are unnecessary and complicated tools, but in reality, maps simplify your life, take complex data sets and display them in a pleasing graphic you can use to answer questions about your word</p>

                  </article>
                    
                </section>
              </div>
            </div>
        </div>
    </section>

<!-- ajout-->

<div class="container rounded    mt-5 ">
<div class="row">

    <div class="col-lg-12">

        <div class="bs-component">
   <div class="page-header ">
               <div class="alert alert-info   text-dark">

            <h4 class="alert-heading"></h4>
            <p class="mb-0 ">

            Statistics touch every aspect of modern life. They underlie many decisions by public authorities, businesses and communities. They provide information on trends and forces that affect our lives. High-quality statistics are essential for making informed decisions. And, we adhere to strict criteria in order to provide timely, accurate and consistent statistics that comply with international standards, without any external intervention.
            <b>This chart represents the number of infected cases in all countries according to the years 2020/2021/2022</b></p>
            </div>
               </div>


               </div></div></div></div>




    <!--ajoute-->
    <div class="container alert" id="chart">
            <div class="row">
                <div class="col-lg-12">
    <h1 class="title">Covid-19 Chart</h1>
<div class="chart_div"></div>
<select class="country_options">
    <option>Morocco</option>
</select>


</div>
</div>
</div>
   <!--ajoute-->





<!--ajoute-->

    <section id="sectiono" class="alert" >

    <div class="container rounded    ">
<div class="row">

    <div class="col-md-12">

        <div class="bs-component">
   <div class="page-header ">
               <div class="alert alert-warning  text-dark">

            <h4 class="alert-heading"></h4>
            <p class="mb-0 ">

            <b>Statistics of infected  and died by  covid 19 every week in the years 2020/2021/2022</b></p>
            </div>
               </div>


               </div></div></div></div>

    <div class="bamo"> <?php echo $data3 ;?> </div>
    <div class="bamo"><?php echo $data4 ;?> </div>
    <div class="bamo"> <?php echo $data5 ;?> </div>









    </section>




<div class="container rounded    ">
<div class="row">

    <div class="col-md-12">
        <div class="card card-body">
        <div id="ajo">
            <h1></h1>
            <p>
                <big style="color:#1ec598">How to protect yourself and others?</big>

                Stay at home and stay safe

                <h2 class="alert alert-primary mb-4 mt-5 col-md-10">1. What is COVID-19 ?</h2>
                A virus linked to the family of severe acute respiratory syndrome coronavirus 2 (SARS-CoV-2) was identified as the cause of a disease outbreak that began in China in 2019. The disease is called coronavirus disease 2019 (COVID-19).

                <h2 class="alert alert-primary mb-4 mt-5 col-md-10">2. How does COVID-19 spread?</h2>
                Several studies have shown that it spreads from person to person among those in close contact (within about 6 feet, or 2 meters). The virus spreads by respiratory droplets released when someone infected with the virus coughs, sneezes or talks.
                <span id="dots">....</span>
                <div id="more">
                    <p>
                        <h2 class="alert alert-primary mb-4 mt-5 col-md-10">3. What are the symptoms of COVID-19?</h2>
                        COVID-19 symptoms can be very mild to severe. Sometime it is asymptomatic. The most common symptoms are fever, cough and tiredness. Other symptoms may include shortness of breath, muscle aches, chills, sore throat, headache, chest pain, and loss of taste or smell etc. Other less common symptoms have also been reported. Symptoms may appear two to 14 days after exposure.

                        <h2 class="alert alert-primary mb-4 mt-5 col-md-10">4. Can COVID-19 be prevented or treated?</h2>
                        No vaccine is available yet for the coronavirus disease 2019 (COVID-19). No medication is recommended to treat COVID-19. Treatment is directed at relieving symptoms.

                        <h2 class="alert alert-primary mb-4 mt-5 col-md-10">5. What can I do to avoid becoming ill?</h2>
                        Wash your hands often with soap and water for at least 20 seconds, or use an alcohol-based hand sanitizer that contains at least 60% alcohol.
                        Wear a surgical/cloth face mask in public areas
                        Keep a distance of about 6 feet, or 2 meters with anyone (who is sick or has symptoms).
                        Cover your mouth and nose with your elbow or a tissue when you cough or sneeze. Throw away the used tissue.
                        Avoid large events and mass gatherings.
                        Avoid touching your eyes, nose and mouth.
                        Clean and disinfect surfaces you often touch on a daily basis.
                        If you have a chronic medical condition and may have a higher risk of serious illness, check with your doctor about other ways to protect yourself.

                        <h2 class="alert alert-primary mb-4 mt-5 col-md-10">6. Should I wear a mask?</h2>
                        We highly recommend wearing surgical/cloth mask in public places, such as the grocery store, where it's difficult to avoid close contact with others. It's especially suggested in areas with ongoing community spread. This updated advice is based on data showing that people with COVID-19 can transmit the virus before they realize they have it. Using masks in public may help reduce the spread from people who don't have symptoms. Non-medical cloth masks are recommended for the public. Surgical masks and N-95 respirators are in short supply and should be reserved for health care providers.

                        <h2 class="alert alert-primary mb-4 mt-5 col-md-10">7. What can I do if I am or may be ill with COVID-19?</h2>
                        COVID-19 symptoms include fever, coughing and shortness of breath, plus additional ones mentioned above. Keep track of your symptoms, which may appear two to 14 days after exposure, and call to seek medical attention if your symptoms worsen, such as difficulty breathing. If you think you may have been exposed to COVID-19, contact your health care provider immediately. Take the following precautions to avoid spreading the illness:
                        Stay home from work, school and public areas, except to get medical care.
                        Avoid taking public transportation if possible.
                        Wear a mask around other people.
                        Isolate yourself as much as possible from others in your home.
                        Use a separate bedroom and bathroom if possible.
                        Avoid sharing dishes, glasses, bedding and other household items.
                        <h2 class="alert alert-primary mb-4 mt-5 col-md-10">8. Understanding the risks</h2>
                        It’s very important to understand that even when people appear not to have symptoms of coronavirus (COVID-19), they may still be carrying the virus. Where you’re meeting people who aren’t from your household, your risk of catching coronavirus can increase depending on the situation. Science Hub assesses the risk (high to low) through COVID-19 risk assessment and communication tool (COVIRA). You can assess your and your loved ones risk; and should take these risks into account when you are thinking about visiting or gathering with other people, in particular the time limits where you may be at a higher risk of catching COVID-19 when spending time with someone indoors. You should also consider the greater risks posed to those who are classified as vulnerable and very vulnerable. When meeting people from outside your household, that is, people you don’t currently live with, you should continue to follow the national guidance, and practice good respiratory hygiene. Who is at a higher risk? Early information of several countries show that older adults, people who live in a nursing home or long-term care facility, and individuals of any age with the conditions below are at higher risk of getting very sick from COVID-19:
                        Have serious underlying medical conditions, particularly if not well controlled, such as heart, lung or liver disease; diabetes; moderate to severe asthma; severe obesity; and chronic kidney disease undergoing dialysis.
                        Have a weakened immune system, including those undergoing cancer treatment, smoking and having other immune compromised conditions.
                    </p>
                </div>
            </p>
            <button id="bton" onclick="readmore()" class="btn bg-info mt-3">Read more</button>
        </div>
        </div>


</div>
  </div>
</div>




<script>
    function readmore(){
        var dots=document.getElementById("dots");
        var moretext=document.getElementById("more");
        var bton=document.getElementById("bton");

        if(dots.style.display==="none"){
            dots.style.display="inline";
            bton.innerHTML="Read more";
            moretext.style.display='none';
        }
        else {
            dots.style.display='none';

            bton.innerHTML="Read less";

            moretext.style.display='inline';
        }
    }
</script>
<!--
<aside id="asido">

    <table id="table" class="alert"></table>
</aside>



<section class="section" id="contact-us">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div id="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3336.590297406127!2d-8.436300284957523!3d33.25103528083153!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda91dc4c0413d23%3A0xc8dbb36f4b2d2cbc!2sENSA%20-%20El%20Jadida!5e0!3m2!1sfr!2sma!4v1652185143348!5m2!1sfr!2sma"  width="100%" height="600px" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div class="contact-form">
                        <form id="contact" action="" method="post">
                          <div class="row">
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="name" type="text" id="name" placeholder="Your Name*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-12 col-sm-12">
                              <fieldset>
                                <input name="subject" type="text" id="subject" placeholder="Subject">
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <textarea name="message" rows="6" id="message" placeholder="Message" required=""></textarea>
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Send Message</button>
                              </fieldset>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    ***** Contact Us Area Ends *****
     ***** Footer Start *****
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2022 ENSAJ

                    - Designed by <a rel="nofollow" href="#" class="tm-text-link" target="_parent">MACOVID</a></p>


                </div>
            </div>
        </div>
    </footer>
-->

    <!-- jQuery -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/imgfix.min.js"></script>
    <script src="assets/js/mixitup.js"></script>
    <script src="assets/js/accordions.js"></script>

    <!-- Global Init -->
    <script src="assets/js/custom.js"></script>
    <script>

        function  opendialog(){
        document.getElementById("kok").setAttribute("open","true");

        }
        function closedialog(){
        document.getElementById("kok").removeAttribute("open");

        }


        </script>

<script src="assets/js/app1.js"></script>


     <script>
    const countrysSelectELement = document.querySelector(".country_options");
let currentCountry;
const chartDiv = document.querySelector(".chart_div");

function displayChart(data) {
  const canvas = document.createElement('canvas');
  canvas.setAttribute("id", "myChart");
  chartDiv.appendChild(canvas);
  const dailyCases = data.map((day, index) => {
      if (index) return Math.abs(day.Confirmed - data[index - 1].Confirmed);
      else day.Confirmed;
  });

  var ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: 'line',

      // The data for our dataset
      data: {
          labels: data.map(day => day.Date),
          datasets: [{
              label: 'Daily Cases',
              backgroundColor: '#BEFEFF',
              borderColor: '#00BEC1',
              data: dailyCases,
              borderWidth: 1,
          }]
      },

      // Configuration options go here
      options: {}
  });

}

function getCovidData(country) {
  const endpoint = `https://api.covid19api.com/total/dayone/country/${country}`;
  fetch(endpoint).then(response => response.json())
    .then(data => {
      chartDiv.innerHTML = "";
      displayChart(data);
    })
    .catch(err => console.warn(err));

}

function getCountries() {
  const endpoint = "https://api.covid19api.com/countries";
  fetch(endpoint).then(response => response.json())
    .then(countries => {
      countries.forEach(country => {
         const countryName = country.Country;
         const option = document.createElement("option");
         option.setAttribute("value", countryName);
         option.innerHTML = countryName;
         countrysSelectELement.appendChild(option);
      });
      currentCountry = countrysSelectELement.children[0].value;
      getCovidData(currentCountry);
    })
    .catch(err => console.warn(err));
}

getCountries();

countrysSelectELement.addEventListener("click", () => {
   const currentIndex = countrysSelectELement.selectedIndex;
   const countrySelected = countrysSelectELement.children[currentIndex].value;
    currentCountry = countrySelected;
   getCovidData(countrySelected);
});
</script>






  </body>
</html>
