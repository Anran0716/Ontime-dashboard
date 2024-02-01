<!--<form action='add.php' method='post'>-->
    <button class="btn btn-sm btn-circle float-right" onclick="closeForm()">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
    <div class="text-3xl md:text-3xl font-bold font-sans text-center">Incident Report</div>
    <div class="divide-y divide-solid">
    <div class="text-sm mb-2">Place a marker on the map to specify the location you wish to report on:
        You may use the search box to locate an exact location or click on the map to drop a marker.</div>
    <div id="questions">
    <div class="mt-1">Describe the Incident Below:</div>
    <textarea id="message" rows="4" class="block p-2.5 w-full rounded-lg text-sm border border-dotted border-slate-300/70 hover:border-solid focus:border-slate-300/80" placeholder="Write your thoughts here..."></textarea>
    <div class="mt-2">Date of Incident:</div>
    <input type='date' name='year' max='<?php echo date('Y-m-d');?>' id="year" placeholder="Type here" class="input mb-2 input-sm border-dotted border-2 border-slate-300/70 hover:border-solid w-full max-w-xs"></input>
    <div class="mt-2">Location:</div>
    <input type='text' name='location' id='location' placeholder="Type here" class="input mb-2 input-sm border-dotted border-2 border-slate-300/70 hover:border-solid w-full max-w-xs"></input>
    <div class="mt-2">Report a Specific Route:</div>
    <select id="route" onchange="displayOneRoute(this.value)" class="bg-indigo-950 text-white select text-base w-full">
        <option disabled selected>Select Route</option>
        <?php
            $sql = "SELECT `route_id`, `route_long_name`, `route_color` FROM `routes` ORDER BY `routes`.`route_id` ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                //echo "<div class='collapse'>";
                $routeid = $row['route_id'];
                echo "<option value='$routeid'>Route $routeid</option>";
            }
            } else {
            echo "No routes found";
            }
        ?>
    </select>
    </div>
    <div class="flex justify-evenly">
        <button type="reset" value="Reset" class="mt-2 btn btn-sm text-black hover:text-white font-bold py-2 px-4 rounded-full">Reset</button>
        <button type='submit' value='Submit' name='submit' onClick="return validation()" class="mt-2 btn btn-sm text-black hover:text-white font-bold py-2 px-4 rounded-full">Submit</button>
    </div>		
    </div>	
<!--</form>-->