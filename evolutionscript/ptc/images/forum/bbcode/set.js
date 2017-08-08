// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// BBCode tags example
// http://en.wikipedia.org/wiki/Bbcode
// ----------------------------------------------------------------------------
// Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings = {
	previewParserPath:	'', // path to your BBCode parser
	markupSet: [
		{name:'Bold', key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name:'Italic', key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name:'Underline', key:'U', openWith:'[u]', closeWith:'[/u]'},
		{name:'Picture', key:'P', replaceWith:'[img][![Url]!][/img]'},
		{name:'Link', key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder:'Your text to link here...'},
		{name:'Bulleted list', openWith:'[list]\n', closeWith:'\n[/list]'},
		{name:'Numeric list', openWith:'[list=[![Starting number]!]]\n', closeWith:'\n[/list]'}, 
		{name:'List item', openWith:'[*] '},
		{name:'Quotes', openWith:'[quote]', closeWith:'[/quote]'},
        {name:'angel', openWith:'(angel)'},
        {name:'angry', openWith:'(angry)'},
        {name:'bigsmile', openWith:'(bigsmile)'},
        {name:'blush', openWith:'(blush)'},
        {name:'brokenheart', openWith:'(brokenheart)'},
        {name:'call', openWith:'(call)'},
        {name:'cash', openWith:'(cash)'},
        {name:'cool', openWith:'(cool)'},
        {name:'crying', openWith:'(crying)'},
        {name:'dance', openWith:'(dance)'},
        {name:'devil', openWith:'(devil)'},
        {name:'doh', openWith:'(doh)'},
        {name:'drunk', openWith:'(drunk)'},
        {name:'dull', openWith:'(dull)'},
        {name:'envy', openWith:'(envy)'},
        {name:'evilgrin', openWith:'(evilgrin)'},
        {name:'fubar', openWith:'(fubar)'},
        {name:'giggle', openWith:'(giggle)'},
        {name:'handshake', openWith:'(handshake)'},
        {name:'happy', openWith:'(happy)'},
        {name:'headbang', openWith:'(headbang)'},
        {name:'heart', openWith:'(heart)'},
        {name:'hi', openWith:'(hi)'},
        {name:'inlove', openWith:'(inlove)'},
        {name:'kiss', openWith:'(kiss)'},
        {name:'lipssealed', openWith:'(lipssealed)'},
        {name:'makeup', openWith:'(makeup)'},
        {name:'mmm', openWith:'(mmm)'},
        {name:'nerd', openWith:'(nerd)'},
        {name:'no', openWith:'(no)'},
        {name:'nod', openWith:'(nod)'},
        {name:'party', openWith:'(party)'},
        {name:'puke', openWith:'(puke)'},
        {name:'punch', openWith:'(punch)'},
        {name:'rock', openWith:'(rock)'},
        {name:'rofl', openWith:'(rofl)'},
        {name:'sad', openWith:'(sad)'},
        {name:'shake', openWith:'(shake)'},
        {name:'sleepy', openWith:'(sleepy)'},
        {name:'smile', openWith:'(smile)'},
        {name:'smirk', openWith:'(smirk)'},
        {name:'speechless', openWith:'(speechless)'},
        {name:'swear', openWith:'(swear)'},
        {name:'sweat', openWith:'(sweat)'},
        {name:'talk', openWith:'(talk)'},
        {name:'thinking', openWith:'(thinking)'},
        {name:'tmi', openWith:'(tmi)'},
        {name:'tongueout', openWith:'(tongueout)'},
        {name:'wait', openWith:'(wait)'},
        {name:'wasntme', openWith:'(wasntme)'},
        {name:'whew', openWith:'(whew)'},
        {name:'wink', openWith:'(wink)'},
        {name:'wondering', openWith:'(wondering)'},
        {name:'worried', openWith:'(worried)'},
        {name:'yawn', openWith:'(yawn)'},
        {name:'yes', openWith:'(yes)'},
	]
}