<?php
// ----------------------------------------------------------------------------
// markItUp! BBCode Parser
// v 1.0.6
// Dual licensed under the MIT and GPL licenses.
// ----------------------------------------------------------------------------
// Copyright (C) 2009 Jay Salvat
// http://www.jaysalvat.com/
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
// Thanks to Arialdo Martini, Mustafa Dindar for feedbacks.
// ----------------------------------------------------------------------------

define ("EMOTICONS_DIR", "images/forum/bbcode/emo/");

function BBCode2Html($text, $maxheight=null) {
	$text = trim($text);
	// BBCode [code]
	if (!function_exists('escape')) {
		function escape($s) {
			global $text;
			$text = strip_tags($text);
			$code = $s[1];
			$code = htmlspecialchars($code);
			$code = str_replace("[", "&#91;", $code);
			$code = str_replace("]", "&#93;", $code);
			return '<pre><code>'.$code.'</code></pre>';
		}	
	}
	$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escape", $text);

	// Smileys to find...
	$in = array(	'(angel)',
					'(angry)',
					'(bigsmile)',
					'(blush)',
					'(brokenheart)',
					'(call)',
					'(cash)',
					'(cool)',
					'(crying)',
					'(dance)',
					'(devil)',
					'(doh)',
					'(drunk)',
					'(dull)',
					'(envy)',
					'(evilgrin)',
					'(fubar)',
					'(giggle)',
					'(handshake)',
					'(happy)',
					'(headbang)',
					'(heart)',
					'(hi)',
					'(inlove)',
					'(kiss)',
					'(lipssealed)',
					'(makeup)',
					'(mmm)',
					'(nerd)',
					'(no)',
					'(nod)',
					'(party)',
					'(puke)',
					'(punch)',
					'(rock)',
					'(rofl)',
					'(sad)',
					'(shake)',
					'(sleepy)',
					'(smile)',
					'(smirk)',
					'(speechless)',
					'(swear)',
					'(sweat)',
					'(talk)',
					'(thinking)',
					'(tmi)',
					'(tongueout)',
					'(wait)',
					'(wasntme)',
					'(whew)',
					'(wink)',
					'(wondering)',
					'(worried)',
					'(yawn)',
					'(yes)',
	);
	// And replace them by...
	$out = array(	'<img src="'.EMOTICONS_DIR.'angel.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'angry.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'bigsmile.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'blush.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'brokenheart.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'call.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'cash.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'cool.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'crying.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'dance.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'devil.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'doh.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'drunk.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'dull.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'envy.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'evilgrin.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'fubar.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'giggle.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'handshake.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'happy.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'headbang.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'heart.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'hi.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'inlove.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'kiss.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'lipssealed.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'makeup.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'mmm.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'nerd.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'no.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'nod.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'party.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'puke.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'punch.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'rock.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'rofl.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'sad.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'shake.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'sleepy.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'smile.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'smirk.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'speechless.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'swear.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'sweat.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'talk.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'thinking.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'tmi.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'tongueout.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'wait.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'wasntme.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'whew.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'wink.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'wondering.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'worried.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'yawn.gif" boder=0 />',
					'<img src="'.EMOTICONS_DIR.'yes.gif" boder=0 />',
	);
	$text = str_replace($in, $out, $text);
	
	// BBCode to find...
	$in = array( 	 '/\[b\](.*?)\[\/b\]/ms',	
					 '/\[i\](.*?)\[\/i\]/ms',
					 '/\[u\](.*?)\[\/u\]/ms',
					 '/\[img\](.*?)\[\/img\]/ms',
					 '/\[email\](.*?)\[\/email\]/ms',
					 '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
					 '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
					 
					 '/\[quote source\="?(.*?)"?\](.*?)\[\/quote\]/ms',
					 '/\[quote source\="?(.*?)"?\](.*?)\[\/quote\]/ms',
					 '/\[quote](.*?)\[\/quote\]/ms',
					 '/\[quote](.*?)\[\/quote\]/ms',
					 
					 
					 
					 
					 '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
					 '/\[list\](.*?)\[\/list\]/ms',
					 '/\[\*\]\s?(.*?)\n/ms'
	);
	// And replace them by...
	$out = array(	 '<strong>\1</strong>',
					 '<em>\1</em>',
					 '<u>\1</u>',
					 '<div style="width:500px;"><img src="\1" alt="\1" style="'.(is_numeric($maxheight)?'max-height:'.$maxheight.';':'').'max-width:468px;" /></div>',
					 '<a href="mailto:\1">\1</a>',
					 '<a href="\1">\2</a>',
					 '<span style="color:\1">\2</span>',
					 '<blockquote class="cellcontent padding10"><em>Quote: <strong>\1</strong><br>\2</em></blockquote>',
					 '<blockquote class="cellcontent padding10"><em>Quote: <strong>\1</strong><br>\2</em></blockquote>',
					 '<blockquote class="cellcontent padding10"><em>Quote:<br>\1</em></blockquote>',
					 '<blockquote class="cellcontent padding10"><em>Quote:<br>\1</em></blockquote>',
					 
					 '<ol start="\1">\2</ol>',
					 '<ul>\1</ul>',
					 '<li>\1</li>'
	);
	$text = preg_replace($in, $out, $text);
		
	// paragraphs
	$text = nl2br($text);
	$text = str_replace("\r", "", $text);
//	$text = "<p>".preg_replace("/(\n){2,}/", "</p><p>", $text)."</p>";

	
	// clean some tags to remain strict
	// not very elegant, but it works. No time to do better ;)
	if (!function_exists('removeBr')) {
		function removeBr($s) {
			return str_replace("<br />", "", $s[0]);
		}
	}	
	$text = preg_replace_callback('/<pre>(.*?)<\/pre>/ms', "removeBr", $text);
	$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);
	
	$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
	$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);
	
	return $text;
}
if($_REQUEST['preview']==1){
$html = BBCode2Html($_POST['message']);
		$stored = array("status"=>1,"msg"=>$html);
		echo json_encode($stored); 
		exit;
}
?>