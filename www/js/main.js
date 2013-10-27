/**
 * removes _fid from url
 * @see http://forum.nette.org/cs/4405-flash-zpravicky-bez-fid-v-url#p43713
 */
if(window.history.replaceState){l=window.location.toString();u=l.indexOf('_fid=');if(u!=-1){u=l.substr(0,u)+l.substr(u+10);if(u.substr(u.length-1)=='?'||u.substr(u.length-1)=='&')u=u.substr(0,u.length-1);window.history.replaceState('',document.title,u)}}

$(function(){

});
