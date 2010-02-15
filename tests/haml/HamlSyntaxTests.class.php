<?php
/**
 * Tag generating tests
 *
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Tests.Haml
 */

final class HamlSyntaxTests extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->parser = new HamlParser(dirname(__FILE__).'/templates/original', false);
		$this->parser->setTmp(dirname(__FILE__).'/../../tmp/haml');
	}

	public function testMultiline()
	{
		$this->parser->setSource("%p Hi |\n there");
		$this->assertEquals("<p>Hi there</p>", $this->parser->fetch());
	}

	public function testPipeSafe()
	{
		$this->parser->setSource("%p Hi\n|\nthere");
		$this->assertEquals("<p>Hi</p>\n|\nthere", $this->parser->fetch());
	}

	public function testMultilineChain()
	{
		$this->parser->setSource("%p Hi |\n|\nthere");
		// This test is gonna be broken for a while
		//$this->assertEquals("<p>Hi |</p>\nthere", $this->parser->fetch());
	}

	public function testElseSafe()
	{
		$this->parser->setSource("- if(false)\n  Hi!\n- else\n  Bye!");
		$this->assertEquals("  Bye!", $this->parser->fetch()); // FIXME: whitespace?
	}

	public function testElseSafe2()
	{
		$this->parser->setSource("#test0\n  #test1\n    - if(false)\n      Hi!\n    - else\n      Bye!");
		$this->assertEquals("<div id=\"test0\">\n  <div id=\"test1\">\n      Bye!\n  </div>\n</div>", $this->parser->fetch()); // FIXME: whitespace?
		$this->parser->setSource("#test0\n  #test1\n    #test2\n      - if(false)\n        Hi!\n      - else\n        Bye!");
		$this->assertEquals("<div id=\"test0\">\n  <div id=\"test1\">\n    <div id=\"test2\">\n        Bye!\n    </div>\n  </div>\n</div>", $this->parser->fetch()); // FIXME: whitespace?
	}

	public function testCommas()
	{
		$this->parser->setSource("%a{ :href => 'a,b,c',:rel=> 'link'}");
		$this->assertEquals('<a href="a,b,c" rel="link"></a>', $this->parser->fetch());
	}

	/**
	 * @expectedException HamlException
	 */
	public function testBadIndent(){
		$this->parser->setSource("Hi\n there");
		$this->parser->fetch();
	}
}
