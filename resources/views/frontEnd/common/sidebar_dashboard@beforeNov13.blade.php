<div class="col-md-5 col-sm-5 col-xs-12">
    <div class="profile-nav alt">
        <section class="panel">
            <div class="user-heading alt clock-row bg-themecolor">
                <h1>{{ date('F d') }}  <a href="{{ url('/system/calendar') }}"><?php if(isset($page)){ if($page = 'dashboard'){ ?> <i class="fa fa-calendar pull-right"></i> <?php }} ?> </a></h1>
                <p class="text-left">{{ date('Y, l') }}</p>
                <?php $hour = date('h');
                        $new_hour = $hour-3;
                ?>
                <p class="text-left"><span id="hh">{{ $new_hour }}</span>:<span id="mm">{{ date('i') }}</span> <span id="aa">{{ date('A') }}</span></p>
            </div>
            <ul id="clock">
                <li id="sec"></li>
                <li id="hour"></li>
                <li id="min"></li>
            </ul>

            <div class="below-clock">
                <div>
                    <i class="fa fa-key"></i> 
                    <div class="key-sec cus-key-sec">
                        <h4>Section Key</h4>
                        <ul type="none">
                            <li> <span class="clr-prple"></span>Incentive Scheme</li>
                            <li><span class="clr-grn"></span> Health Record </li>
                            <li><span class="clr-skyblu"></span> Daily Record </li>
                            <li><span class="clr-ornge"></span> Placement Plan </li>
                            <li><span class="clr-red"></span> Needs Urgents attention </li>
                        </ul>
                     </div>
                </div>
                <!-- <ul class="below-list" type="none">
                    <li> <span class="bg-red"></span>  Ian Williams - Management Plans </li>
                    <li> <span class="bg-red"></span>  Jeremy Fisher - Management Plans </li>
                    <li> <span class="bg-red"></span>  Steven Hall - Management Plans </li>

                </ul> -->
                <ul class="below-list" type="none">
                    <?php
                        $plan_noti = App\Notification::dashboardPlanEventNotification();

                        foreach ($plan_noti as $key => $value) { ?>
                    <li> <span class="bg-red"></span>  {{ ucfirst($value['su_name']) }} - {{ ucfirst($value['description']) }} </li>
                    <?php } ?>
                   

                </ul>
                <div class="below-divider"></div>
                <!-- <ul class="below-list" type="none">
                    <li> <span class="bg-seagreen"></span>  Ian Williams - Eye Exams </li>
                    <li> <span class="bg-blue"></span>  Jeremy Fisher - Management Plans </li>
                    <li> <span class="bg-purple"></span>  Lewis Dance - Incentive scheme booking </li>
                    <li> <span class="bg-orange"></span>  Lewis Dance - Placement plan task </li>

                </ul> -->
                <ul class="below-list" type="none">
                    <?php
                        $noti = App\Notification::dashboardEventNotification(); 
                        foreach($noti as $key => $value ) { ?>
                    <li> <span class="{{ $value['color_class'] }}"></span>  {{ ucfirst($value['su_name']) }} - {{ ucfirst($value['description']) }} </li>
                    <?php    } ?> <!-- bg-seagreen -->
                </ul>
            </div>

        </section>

    </div>
</div>

<script>
    $(document).ready(function(){

        // function update_time(){
       
        //     /*var hours = new Date().getHours();*/
        //     var hours = new Date().getHours();
            
        //     //var dt = new Date();
            

        //     var mins = new Date().getMinutes();
        //     var mid='am';
            
        //     if(hours==0){ //At 00 hours we need to show 12 am
        //         hours=12;
        //     }
        //     else if(hours>12)
        //     {
        //         hours=hours-12;
        //         mid='pm';
        //     }

        //     if(hours < 10){
        //         hours = '0'+hours;
        //     }
        //     if(mins < 10){
        //         mins = '0'+mins;
        //     }
        //     $("#hh").text(hours);
        //     $("#mm").text(mins);
        //     $("#aa").text(mid);
        // }
        // window.setInterval(update_time,1000);



        function calcTime(){
            var city = "London";
            // var offset = "-10";  //by karan working perfectly in our system but not in client's sys.
            var offset = "-13"; // -13 use for time becoming less then 3hours to original time
            var d = new Date();
            // subtract local time zone offset
            // get UTC time in msec
            var utc = d.getTime() - (d.getTimezoneOffset() * 60000);

            // create new Date object for different city
            // using supplied offset
            var nd = new Date(utc + (3600000*offset));

            //alert(nd);
            // return time as a string
            //return  nd.toLocaleString();
            var hours = nd.getHours();
            var mins  =  (nd.getMinutes()<10?'0':'') + nd.getMinutes();
            var mid   ='AM';
            
            if(hours == 0){ //At 00 hours we need to show 12 am
                hours = 12;
            
            } else if(hours >= 12) {
                hours = hours - 12;
                mid = 'PM';
            }

            if(hours < 10){
                hours = '0'+hours;
            }
            // if(mins < 10){
            //     mins = '0'+mins;
            // }

            $("#hh").text(hours);
            $("#mm").text(mins);
                   
        }
        window.setInterval(calcTime,1000);

    });
</script>