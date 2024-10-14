(function($){
  function updateWidget(data) {
    if (0 < data.length) {
      $('#wp_fail2ban_last_messages tbody tr').remove();

      for (i in data) {
        const message = data[i];
        const alt = (0 == (i % 2)) ? 'alternate' : '';
        const prio = message['lvl'].toLowerCase();
        const html = `
<tr class="${alt}">
  <td class="dt"><nobr><tt>${message['dt']}&nbsp;Z</tt></nobr></td>
  <td class="priority-${prio}">${message['lvl']}</td>
  <td>${message['msg']}</td>
</tr>
`;
        $('#wp_fail2ban_last_messages tbody').append(html);
      }
    }
  }
  $(document).on('heartbeat-send', function(e, data) {
    data['wp_fail2ban'] = 'wp_fail2ban_last_messages';
  });
  $(document).on('heartbeat-tick', function(e, data) {
    if (data['wp-fail2ban-last-messages']) {
      updateWidget(data['wp-fail2ban-last-messages']);
    }
  });
}(jQuery));

