//
// CALL FLOW CONTENT FUNCTIONS
//


//-- Activate call content


//-- Activate call content

web2t.calls.activateCallContent = function () {
    $('#hist').appendTo($('#'+c.activeName+'Container .callLeftContent'));
    $('.menuButton.active').removeClass("active");
    $('.mainContainer.active').removeClass("active");
    $('#'+c.activeName+'Button').addClass("active");
    $('#'+c.activeName+'Container').addClass("active");
    web2t.calls.manageCallInfluenceButtons();
}
  
    
    
//-- Leg finished

web2t.calls.onLegFinished = function (callName, leg, time) {
    setTimeout( function() { 
        if ($.inArray(n.callName,c.finishedNames)==-1) {
        // if ($.inArray(n.callName,h.names)==-1) {
            $('#'+callName+'Leg'+leg+'Box').addClass("nonActive"); 
        }
    } , time );
}



//-- Call finished

web2t.calls.onCallFinished = function (callName, time) {
    if ( !($('#'+callName+'Container').hasClass('callFinished')) ) {
        c.finishedNr++;
        c.finishedNames.push(callName);
        c.activeOngoingNames.splice($.inArray(n.callName,c.activeOngoingNames),1);
        c.activeFinishedNames.push(n.callName);
        c.statusPhases[callName].finished = true;
        
        // For the case when we log in when we have logged when the call was initiated
        // and we do not receive further notifications for leg 1 before the call ends
        if (c.calledParties[callName]=="") {
            c.calledParties[callName] = callName;
        }
        
        if (callName==c.activeName) {
            web2t.calls.manageCallInfluenceButtons();
        }
        
        var currentTimeFormatted = new Date().toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
        setTimeout( function() {web2t.calls.history.callFinishedToHistory(callName,currentTimeFormatted);} , time );
        
        $('#'+callName+'Button').addClass('callFinished');
        $('#'+callName+'Container').addClass('callFinished');               
    }
}








 
    
//
// CALL INFLUENCE FORM
//


//-- Button clicked on call influence form
$(document).on('click',"#callInfluenceForm input[type=submit]", function(){        
    c.influenceClickedButton = $(this).attr('id');//.substr(0,-6);
    c.influenceClickedButton = c.influenceClickedButton.substr(0,c.influenceClickedButton.length-6);
});
    
   
   
//-- Enter pressed on call influence form
   
$(document).on('keypress keydown keyup', '#numberToCall', function(e) {
    if (e.keyCode==13) {
        if ( $('#callInfluenceForm input[type=submit]').length>1 ) { 
            e.preventDefault(); 
    }
    else {
        c.influenceClickedButton = 'clickToCall';
        }
    }         
});
    
 
 
//-- Mannage buttons on call influence form

web2t.calls.manageCallInfluenceButtons = function () {
    if (  (c.type[$.inArray(c.activeName,c.name)]=='oc') || (c.statusPhases[c.activeName].finished) || (!c.statusPhases[c.activeName].ringing.call) ) {
        $('#transferSubmit').remove();
        $('#deflectionSubmit').remove();
    }
    else if ( (c.statusPhases[c.activeName].answered.call) && ($('#transferSubmit').length==0) ) {
        $('#deflectionSubmit').remove();
        $('<input type="submit" id="transferSubmit" name="transferSubmit" value="Transfer"/>')
            .appendTo($('#callInfluenceForm')).hide().show("fade",{},10).removeAttr('style');
    }
    else if ( (c.statusPhases[c.activeName].ringing.call) && ($('#deflectionSubmit').length==0) ) {
        $('#transferSubmit').remove();
        $('<input type="submit" id="deflectionSubmit" name="deflectionSubmit" value="Deflection"/>')
            .appendTo($('#callInfluenceForm')).hide().show("fade",{},10).removeAttr('style');
    }
}



//-- Actions performed when call influence form is submitted


web2t.calls.onCallInfluenceFormSubmit = function () {
    var numberToCall = web2t.miscellany.normalizeNumber($('#numberToCall').val());
    switch(c.influenceClickedButton) {
        case 'clickToCall': 
            web2t.cometd.messages.callInfluence.cometdData.pa_TargetPhoneNumber_pa = 'CC'+numberToCall;
            break;
        case 'deflection':
            web2t.cometd.messages.callInfluence.cometdData.pa_TargetPhoneNumber_pa = 'CD'+numberToCall;
            break;
        case 'transfer':
            web2t.cometd.messages.callInfluence.cometdData.pa_TargetPhoneNumber_pa = 'CT'+numberToCall;
            // alert(web2t.cometd.messages.callInfluence.cometdData.toSource());
            break;
    }
    web2t.cometd.sendMessage.callInfluence(); 
    c.influenceClickedButton = "";
}










//
// CALL LOG
//


//-- Click on log entry

$(document).on('click',".mainContainer.active .logEntry",function(){
    if ( $(this).hasClass('logLegEntry') ) { var elemClass = 'logLegEntry'; }
    else if ( $(this).hasClass('logStatusEntry') ) { var elemClass = 'logStatusEntry'; }
    else if ( $(this).hasClass('logTimeEntry') ) { var elemClass = 'logTimeEntry';   }
    else if ( $(this).hasClass('logGapEntry') ) { var elemClass = 'logGapEntry'; }
    
    if ($.inArray(c.activeName,h.names)>=0) {
        var index = $('.mainContainer.active  .'+elemClass).index($(this));
        web2t.calls.logEntriesInteraction(index);
    }   
}); 



//-- Log entries interaction
 
web2t.calls.logEntriesInteraction = function (index) {
    $('.mainContainer.active .logEntry.highlighted').removeClass("highlighted");

    var legs = [];
    
    for (var i=index; i>=0; i--) {
        var logNotif = c.logNotifs[c.activeName][i];
        if ($.inArray(logNotif.leg,legs)==-1) {
            // alert("Index: "+i+" ,   n.leg:"+logNotif.leg);
            web2t.calls.arrows.drawUserNotifInfo(c.activeName, logNotif, false, true);      
            legs.push(logNotif.leg);
            // $('.mainContainer.active .leg'+logNotif.leg+'Box .arrow').removeAttr(^^'class').addClass("arrow state"+logNotif.stateNameClass);
            $('.mainContainer.active .logLegEntry:eq('+i+')').addClass("highlighted");
            $('.mainContainer.active .logStatusEntry:eq('+i+')').addClass("highlighted");
            $('.mainContainer.active .logTimeEntry:eq('+i+')').addClass("highlighted");
            $('.mainContainer.active .logGapEntry:eq('+i+')').addClass("highlighted");
            
            $.each(g.additionalInfoTitle, function (index, value) {
                $('#'+c.activeName+'AuxLeg'+logNotif.leg+'InfoBox .'+index+' .value').text(logNotif[index+'Orig']);
            });
        }   
    }
    
    $('.mainContainer.active .legBox').each( function(index) {
        var currLeg = $(this).attr("id").substr(8,1);
        if (( $.inArray(currLeg,legs)==-1) && !($(this).hasClass("nonActive")) )
            $(this).addClass("nonActive");
        else if ( ($.inArray(currLeg,legs)>=0) && $(this).hasClass("nonActive") )
            $(this).removeClass("nonActive");
    });
}
 
 
 
//-- Log time entries interaction

$(document).on('click',".mainContainer.active .timeButton",function () {
    $(this).toggleClass('displayTime');      
    if ( !($(this).hasClass('displayTime')) ) {
        $('.mainContainer.active .logTimeElem').removeClass('active');
    }
    $('.mainContainer.active .callLog').toggleClass('displayTime');            
    $('.mainContainer.active .callLogBackground').toggleClass('displayTime');            
    $('.mainContainer.active .callLogBackgroundTime').toggleClass('displayTime'); 
    if ( ($(this).hasClass('displayTime')) ) {                       
        // $('.mainContainer.active .logTimeElem').toggleClass('active');
        $('.mainContainer.active .logTimeElem').show("fade",{},100,function(){
             $('.mainContainer.active .logTimeElem').addClass('active').removeAttr('style');
         });
    }               
});
   
   
   
   
   
   
   


    
//
// MENU
//


//--  Manage button borders
web2t.calls.manageMenuButtonBorders = function () {
    var numButtons = $('.menuButton').length;
    var indexes = ["first", "second", "third"];
    $('.menuButton').each( function(index) {
        $(this).removeClass("first second third last");
        $(this).addClass(indexes[index]);
        if (index==(numButtons-1)) {
            $(this).addClass("last");
        }
    });
}
 
 
 
//-- Tabs interaction. Click on call tab.

$(document).on('click',"#callsMenu li",function() {
    if ( !($(this).hasClass("active")) ) {
        c.activeName = $(this).attr("id").substr(0,5);
        
        if ( $('#hist .histCallEntry').hasClass("highlighted") ) {
            web2t.calls.history.unhighlightHistoryEntry();
        }
        else if ( $(this).hasClass("histButton") ) {
            web2t.calls.history.highlightHistoryEntry();
        }
        
        web2t.calls.activateCallContent();
    }
});  
         


   
   
   
   
   
     
    
//
// ARROWS
//


//-- Fill arrow with its correspondent content

web2t.calls.arrows.drawUserNotifInfo = function  (callName, logNotif, firstTime, validImageClasses) { 
    var id = callName+'Leg'+logNotif.leg+'Arrow';       
    var canvas = document.getElementById(id);
    var dc = canvas.getContext("2d");
        
    // Change device image corresponding to the leg
    if ( ($('#'+callName+'Leg'+logNotif.leg+'User img').length!=0) && (validImageClasses) &&  (!($('#'+callName+'Leg'+logNotif.leg+'User img').hasClass(logNotif.deviceType+"LegDevice"))) ) {
        $('#'+callName+'Leg'+logNotif.leg+'User img').remove();
        $('#'+callName+'Leg'+logNotif.leg+'User').prepend('<img id="'+callName+'Leg'+logNotif.leg+'Device" class="leg'+logNotif.leg+'Device legDevice '+logNotif.deviceType+'LegDevice" src="images/web/d_'+logNotif.deviceType+'.png"/>');
    }
        
    if (firstTime) {        
        canvas.width = 160;
        canvas.height = 80;
    }
    
    // dc.clearRect(0, 0, canvas.width, canvas.height);
    dc.globalCompositeOperation = "source-over";
    
    web2t.calls.arrows.drawLegArrow(canvas);
    dc.fillStyle = stateColor[logNotif.stateNameClass];
    dc.fill();
    
    if (logNotif.diffCalledParty) {
        web2t.calls.arrows.drawArrowSec(dc);
    }
    
    dc.fillStyle = "#4B4B4B";
    dc.font = "20px tele-grotesknor";
    
    if ( (logNotif.leg<3) || (logNotif.callType=='oc') ) {
        dc.fillText("S",110,47);
        if ( (logNotif.leg<3) && (logNotif.callType=='oc') ) {
            dc.fillText("S",40,47);
        }
    }
}


    
//-- Draw a leg arrow

web2t.calls.arrows.drawLegArrow = function (canvas) {
    var dc = canvas.getContext("2d");
    // Points which are correct (when I draw straight lines its a perfect arrow
    var width   = canvas.width;
    var height  = canvas.height;
    var arrowW  = 40;
    var arrowH  = 30;
    var p1      = {x: 0, y: height/2};
    var p2      = {x: arrowW, y: 0};
    var p3      = {x: arrowW,         y: (height-arrowH)/2};
    var p4      = {x: (width-arrowW), y: (height-arrowH)/2};
    var p5      = {x: (width-arrowW), y: 0};
    var p6      = {x: width,          y: height/2};
    var p7      = {x: (width-arrowW), y: height};
    var p8      = {x: (width-arrowW), y: height-((height-arrowH)/2)};
    var p9      = {x: arrowW,         y: height-((height-arrowH)/2)};
    var p10     = {x: arrowW, y: height};

    dc.beginPath();
    dc.moveTo(p1.x, p1.y);
    dc.lineTo(p2.x, p2.y); dc.lineTo(p3.x, p3.y); dc.lineTo(p4.x, p4.y); dc.lineTo(p5.x, p5.y);
    dc.lineTo(p6.x, p6.y); dc.lineTo(p7.x, p7.y); dc.lineTo(p8.x, p8.y);  dc.lineTo(p9.x, p9.y);
    dc.lineTo(p10.x, p10.y); dc.lineTo(p1.x, p1.y);
    dc.closePath();
}



//-- Draw a small arrow inside leg arrow

web2t.calls.arrows.drawArrowSec = function (dc) {
    // Points which are correct (when I draw straight lines its a perfect arrow
    var arrowW  = 40;
    var arrowH  = 30;
    var p1       = {x: 40, y: 30};
    var p2       = {x: 40, y: 45};
    var p3       = {x: 60, y: 45};
    var p4       = {x: 55, y: 40};
    var p5       = {x: 55, y: 50};
    
    dc.beginPath();
    dc.moveTo(p1.x, p1.y);
    dc.lineTo(p2.x, p2.y); dc.lineTo(p3.x, p3.y); dc.lineTo(p4.x, p4.y); dc.moveTo(p3.x, p3.y);
    dc.lineTo(p5.x, p5.y);
    dc.closePath();
    dc.strokeStyle = "#4B4B4B";
        dc.stroke();
}










//
// HISTORY OF CALLS
//


//-- Finished call saved in history  +  display changes

web2t.calls.history.callFinishedToHistory = function (callName, currentTimeFormatted) {
    if ( $.inArray(callName,h.names)==-1 ) {
    
        if ( (g.logged==true) || (c.activeNr<3) ) {
            $('#'+callName+'Button').remove();
        }

        c.activeNr--;
        c.activeNames.splice( $.inArray(callName,c.activeNames), 1 );
        h.number++;
        h.names.push(callName);
        c.activeFinishedNames.splice($.inArray(callName,c.activeFinishedNames),1);
        
            
        // Check if the history is already full
        if (h.activeNr<5) {
            h.activeNr++;
        }
        else {
            $('#hist .histCallEntry').first().remove();
            $('#hist .histTimeEntry').first().remove();
            $('#'+h.names[0]+'Container').remove();
            h.names.splice( 0, 1 ); //index zero, one element
            if (h.activeIndex==0) {
                var index = (h.names.length)-1;
                web2t.calls.history.goToHistory(index); 
            }               
        }
        
        // Add history entry
        // 'Call '+callName[4]+ '             
        $('<span id="histCallEntry'+callName+'" class="histCallEntry histEntry">'+c.calledPartiesDisplay[callName]+'</span>')
            .appendTo($('#hist .histCallBox')).hide().show("fade",{},100).removeAttr('style');
        $('<span id="histTimeEntry'+callName+'" class="histTimeEntry histEntry">'+currentTimeFormatted+'</span>')
            .appendTo($('#hist .histTimeBox')).hide().show("fade",{},100).removeAttr('style');
            
        // Manage application elements on new call  
        if ( $('#'+callName+'Container').hasClass("active") && (c.activeNr<2) ) {
            if (c.activeNr==1) {
                c.activeName = c.activeNames[c.activeNr-1];
                if ( $('#hist .histCallEntry').hasClass("highlighted") ) {
                    web2t.calls.history.unhighlightHistoryEntry();                  
                }
                web2t.calls.activateCallContent();
            }
            else {
                var index = (h.names.length)-1;
                web2t.calls.history.goToHistory(index); 
            }
        }   
            
        web2t.calls.manageMenuButtonBorders();

        // Part of simulation
        // web2t.notifTreatment.receiveOnCallInfoMsg(ONCALLINFO_OC21);
        // web2t.notifTreatment.receiveOnCallInfoMsg(ONCALLINFO_OC22);
    }   
}

    

//-- Click on history entry 
    
$(document).on('click', "#hist .histEntry", function() {
    if ( $(this).hasClass('histCallEntry') ) { var elemClass = 'histCallEntry'; }
    else if ( $(this).hasClass('histTimeEntry') ) { var elemClass = 'histTimeEntry'; }
    
    if ( !($(this).hasClass("active")) ) {
        var index = $('#hist .'+elemClass).index($(this));
        web2t.calls.history.goToHistory(index); 
    }   
}); 



//-- Go to history
    
web2t.calls.history.goToHistory = function (index) {
    // update variables
    h.activeIndex = index;
    h.activeName = h.names[index];
    c.activeName = h.names[index];
    
    // Add call tab to the menu if necessary
    if (  $('.histButton').length==0  ) {
        $('<li id="'+c.activeName+'Button" class="menuButton histButton">History</li> \
            ').prependTo($('#callsMenu'));
        web2t.calls.manageMenuButtonBorders();
    }
    else {
        $('#callsMenu .histButton').attr('id',(c.activeName+'Button'));
    }
        
    // Highlight clicked element
    if ( $('#hist .histCallEntry').hasClass("highlighted") ) {
        web2t.calls.history.unhighlightHistoryEntry(h.activeIndex);                    
    }
    web2t.calls.history.highlightHistoryEntry(index);
        
    // Activate the menu (case: when we have no active call)
    // if ($('#callsMenuContainer').hasClass("nonActive")) {
        $('#callsMenuContainer.nonActive').removeClass("nonActive");
    // }
    
    web2t.calls.activateCallContent();
    
    // highlight last status for each leg
    var ind = $('#'+c.activeName+'Container .logLegEntry').length  - 1;
    web2t.calls.logEntriesInteraction(ind); 
}    



//-- Highlight history entry  

web2t.calls.history.highlightHistoryEntry = function () {
    $('#hist .histCallEntry:eq('+h.activeIndex+')').addClass("highlighted");
    $('#hist .histTimeEntry:eq('+h.activeIndex+')').addClass("highlighted");
}



//-- Unhighlight history entry    

web2t.calls.history.unhighlightHistoryEntry = function () {
    $('#hist .histEntry.highlighted').removeClass("highlighted");
}  























    
    
  
    
    
    
    
    