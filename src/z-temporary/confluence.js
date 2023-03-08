import '../z-style/confluence.scss';

console.log('confluence');

// Area: `.wiki-content`
if (window.$) {
  // Format
  $('.wiki-content>p>br').hide();
  // Table
  $('.wiki-content td.confluenceTd>a').after("<br />");
}
