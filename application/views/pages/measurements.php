<div class = "list-container">
    <a id = "add-measurement" href = "#" class = "add-link" >Add measurement</a>
    <div style = "clear:both;"></div>
    <ul id = "measurement-types-list" class = "items-list">
    </ul>
    <div id = "measurement-type-description" class = "desc-box"></div>
    <div style = "clear:both;"></div>
</div>

<div class = "details-container">
  <a href="#" id = "add-measurement-entry" class = "add-link">Add entry</a>
  <table id = "measurements-log-list" cellspacing="0">
        <thead>
            <tr>
                <th>Value</th>
                <th>Date</th>
                <th class = "image-column no-pad no-right" ></th>
                <th class = "image-column no-pad" ></th>
            </tr>
        </thead>
      <tbody>
      </tbody>
  </table>
</div>
<?php
    echo html::script(array
          (
          'media/js/measurements.js',
          'media/js/timepicker.js',
          ), FALSE);

    echo View::factory('popups/measurement-popup');