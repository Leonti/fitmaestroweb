<style type="text/css" media="screen">
    #holder {
        height: 250px;
        width: 800px;
        margin: 0;
        padding: 0;
    }
</style>



<div class = "details-container">
Start: <input type = "text" id = "start-date" value = "<?php echo date("m/d/Y", strtotime('-1 month')); ?>" /> 
End: <input type = "text" id = "end-date" value = "<?php echo date("m/d/Y"); ?>" /> 
<input type = "submit" value = "Refresh" id = "get-stats" /><br /><br />
Stats type:
<select id = "stats-type">
    <option value = "weight_log">Weight Log</option>
    <option value = "exercise_log">Exercise Progress</option>
</select>
<div id = "exercise-holder" style = "display: none;">
    <span id = "exercise-label">Exercise: </span><span id = "exercise-name"></span>
    <a href = "#" id = "exercise-change">Click to change</a>
    <select id = "stats-subtype">
        <option value = "max">Max reps/weight</option>
        <option value = "total">Weight/reps sum</option>
    </select>
</div>
<table id="data">
            <tfoot>

                <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>

                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>

                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>

                    <th>18</th>
                    <th>19</th>
                    <th>19</th>
                    <th>20</th>
                    <th>22</th>
                    <th>23</th>

                    <th>24</th>
                    <th>25</th>
                    <th>26</th>
                    <th>27</th>
                    <th>28</th>
                    <th>29</th>

                    <th>30</th>
                    <th>31</th>

                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>

                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>

                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>

                    <th>18</th>
                    <th>19</th>
                    <th>19</th>
                    <th>20</th>
                    <th>22</th>
                    <th>23</th>

                    <th>24</th>
                    <th>25</th>
                    <th>26</th>
                    <th>27</th>
                    <th>28</th>
                    <th>29</th>

                    <th>30</th>
                    <th>31</th>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>8</td>

                    <td>25</td>
                    <td>27</td>
                    <td>25</td>
                    <td>54</td>
                    <td>59</td>
                    <td>79</td>

                    <td>47</td>
                    <td>27</td>
                    <td>44</td>
                    <td>44</td>
                    <td>51</td>
                    <td>56</td>

                    <td>83</td>
                    <td>12</td>
                    <td>182</td>
                    <td>52</td>
                    <td>12</td>
                    <td>40</td>

                    <td>8</td>
                    <td>60</td>
                    <td>29</td>
                    <td>7</td>
                    <td>33</td>
                    <td>56</td>

                    <td>25</td>
                    <td>1</td>
                    <td>78</td>
                    <td>70</td>
                    <td>68</td>
                    <td>2</td>

                    <td>8</td>

                    <td>25</td>
                    <td>27</td>
                    <td>25</td>
                    <td>54</td>
                    <td>59</td>
                    <td>79</td>

                    <td>47</td>
                    <td>27</td>
                    <td>44</td>
                    <td>44</td>
                    <td>51</td>
                    <td>56</td>

                    <td>83</td>
                    <td>12</td>
                    <td>91</td>
                    <td>52</td>
                    <td>12</td>
                    <td>40</td>

                    <td>8</td>
                    <td>60</td>
                    <td>29</td>
                    <td>7</td>
                    <td>33</td>
                    <td>56</td>

                    <td>25</td>
                    <td>1</td>
                    <td>78</td>
                    <td>70</td>
                    <td>68</td>
                    <td>2</td>

                </tr>
            </tbody>
        </table>
        <div id="holder"></div>


</div>
<?php
    echo html::script(array
          (
          'media/js/raphael-min.js',
          'media/js/plugins/raphael.path.methods.js',
          'media/js/statistics.js',
          ), FALSE);

    echo html::stylesheet(array
        (
            'media/css/statistics',
        ),
        array
        (
            'screen, print',
        ));

    $selector = new View('popups/selector-popup'); 
    $selector->exercisesArray = $exercisesArray;
    $selector->groups = $groups;
    $selector->render(TRUE);
?>
 
