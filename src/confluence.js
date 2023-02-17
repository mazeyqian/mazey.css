import './z-style/confluence.scss';

console.log('confluence');

// Area: `.wiki-content`
if (window.$) {
  $('.wiki-content>p>br').hide();
  $('.wiki-content td.confluenceTd>a').after("<br />");
}
