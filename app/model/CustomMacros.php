<?php

namespace App;

use Nette\Latte\Compiler;
use Nette\Latte\MacroNode;
use Nette\Latte\Macros\MacroSet;
use Nette\Latte\PhpWriter;


class CustomMacros extends MacroSet
{

	public static function install(Compiler $compiler)
	{
		$set = new static($compiler);
		$set->addMacro('page', callback($set, 'macroPage'), NULL);
	}


	/**
	 * {page $name}
	 * renders hmtl
	 */
	public function macroPage(MacroNode $node, PhpWriter $writer)
	{
		return $writer->write('list($name) = %node.array; echo $control->orm->pages->getByName($name);');
		// return $writer->write('if ($_l->tmp = array_filter(%node.array)) echo \' id="\' . %escape(implode(" ", array_unique($_l->tmp))) . \'"\'');
	}

}
