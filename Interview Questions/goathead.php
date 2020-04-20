<?php
/**
 * @author Tony Perez <aperez1997@yahoo.com>
 */

$perms = getAllPermutations('goat');

//print_r($perms);

goat('goat', 'head');

class goatNode
{
	/** @var goatNode|null */
	public $parent;
	/** @var string */
	public $word;
	/** @var int */
	public $depth;

	public function __construct(goatNode $parent = null, $word, $depth)
	{
		$this->parent = $parent;
		$this->word = $word;
		$this->depth = $depth;
	}

	public function __toString()
	{
		return "{$this->word}{$this->depth}";
	}
}

function goat($start, $end)
{
	$queue = array();
	$node = new goatNode(null, $start, 0);
	$queue[] = $node;

	$hash = array();
	$permNode = null;
	$found = false;
	$totalCount = 0;
	do {
		/** @var goatNode $newNode */
		$totalCount++;
		$newNode = array_shift($queue);
		$newDepth = $newNode->depth + 1;
		$perms = getAllPermutations($newNode->word);
		printf("Perms of [%s] => [%s]\n", $newNode, join(',', $perms));
		foreach ($perms as $newWord){
			$permNode = new goatNode($newNode, $newWord, $newDepth);
			$newWordLower = strtolower($newWord);
			if ($newWordLower == $end){
				$found = true;
				break;
			}
			if (array_key_exists($newWordLower, $hash)){
				// don't revisit old words
				continue;
			}
			$hash[$newWordLower] = true;
			$queue[] = $permNode;
		}
		if ($found){
			break;
		}
		//printf("Queue [%s]\n", join(',', $queue));
		unset($permNode);
	} while (!empty($queue) && $newDepth < 6);

	printf("Total Count %s\n", $totalCount);
	if (isset($permNode) && $permNode instanceof goatNode){
		echo "success {$newDepth}\n";
		print_r($permNode);
	} else {
		echo "failed {$newDepth}\n";
	}
}

function getAllPermutations($word)
{
	$pspell_link = pspell_new("en");
	$alphaBet = range('A', 'Z');
	$output = array();
	for ($i = 0; $i < strlen($word); $i++){
		foreach ($alphaBet as $letter){
			$otherWord = $word;
			$otherWord[$i] = $letter;
			//printf("OtherWord [%s] at %s\n", $otherWord, $i);
			if (strtolower($otherWord) == strtolower($word)){
				// don't create the same word again
				continue;
			}
			if (pspell_check($pspell_link, strtolower($otherWord))){
				$output[] = $otherWord;
			}
		}
	}
	return $output;
}
