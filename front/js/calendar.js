var appCalendar = {

    init: function() {

        //Initialisation de l'application
        console.log('appcalendar.init');
        //Initialisation d'un nouvelle date egale a la date d'aujourd'hui
        var date = new Date();
        //Generation du calendrier avec en parametre le mois et l'année actuelle
        appCalendar.generate(date.getMonth(), date.getFullYear());

        //Ajout de 2 event listener pour les bouton suivant et precedent
        $('.calendar-switch').on('click', appCalendar.switchCalendar);
    },

    currentYear: 0,
    currentMonth: 0,
    dayData: ["lundi","mardi","mercredi","jeudi","vendredi","samedi","dimanche"],
    monthData: ["janvier", "fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","décembre"],

    generate: function(month, year) {

        //On stock le mois et l'année
        appCalendar.currentMonth = month;
        appCalendar.currentYear = year;

        //On recupere le nombre totale de jour du mois
        var totalDay = appCalendar.getTotalDayOfMonth(year, month);

        // On recupere le mois reel comme month est donné par rapport a un
        // tableau commençant par zero on lui ajoute 1
        var realMonth = month + 1;
        var realMonthWithZero = ("0"+realMonth).slice(-2);
        //On recupere toute les tâches
        var dateTask = $(".task-main");

        //On affiche le mois et l'année
        appCalendar.addTitle(month, year);
        //On affiche les jour (lundi, mardi, ...)
        appCalendar.addDayTextual(month, year);

        /* Petit traitement qui permet de recuperer le jour du premier du mois en cours
        et de rajouter des case vide avant */
        var day = new Date(year, month, 1);
        var index = day.getDay();
        if(index === 0) {
            index = 7;
        }
        for (var d = 1; d < index; d++) {
            var $divJour = $('<div class="locked-day-calendar"></div>');
            $('#calendar-container').append($divJour);
        }

        //Debut de la boucle pour l'ajout des jour du mois
        for (var d = 1; d <= totalDay; d++) {

            //On stock le lien du jour
            let realRouteForShow = routeCalendarShow.replace('day', year+'-'+realMonthWithZero+'-'+("0"+d).slice(-2));
            //Affichage de la case du jour et on le rend droppable
            let $divJour = $('<div id="'+d+'-'+realMonth+'-'+ year+'" data-day="'+d+'" class="day-calendar" onclick="location.href=\''+realRouteForShow+'\'"><span class="day-calendar-title" id="day-calendar-title-'+d+'">'+d+'</span></div>');
            $('#calendar-container').append($divJour);
            appCalendar.setDroppable($divJour);
        }
        //On parcoure toute les taches et si un jour est egale a data-time de la tache on la met dedans
        dateTask.each(function() {
            let $divJour = $('#'+$(this).attr('data-time'));
            if($divJour.length) {
                var $task = $(this).clone();
                $($task).addClass('d-none d-md-block');
                $($task).appendTo($divJour);
                $($divJour).css('color', 'red !important');
                appCalendar.setDraggable($task);

                let $divJourTitle = $('#day-calendar-title-'+ $divJour.data('day'));
                if(!$divJourTitle.hasClass('animation-jour')) {
                    $divJourTitle.addClass('animation-jour');
                }
            }
        });
    },

    setDraggable: function(element) {
        $(element).draggable({
            revert:'invalid',
            helper: "clone",
            iframeFix: true,
            containment: '#calendar-container',
            cursorAt: {left: -15, top: -15},
        });
    },

    setDroppable: function(element) {
        $(element).droppable({
                drop: function( event, ui ) {
                    var $tache = $(ui.draggable);
                    $(this).append($tache);
                    $($tache).css("position", "");
                    appCalendar.updateTask($tache, this);
                },
            });
    },
    addTitle: function(month, year) {
        $('#title-calendar').text(appCalendar.monthData[month] + ' '+ appCalendar.currentYear);
    },

    addDayTextual: function(month, year) {
        for (var i = 0; i <= 6; i++) {
            $('#calendar-textual-day').append('<div class="day-textual-calendar d-none d-md-block">'+appCalendar.dayData[i]+'</div>');
        }
        for (var i = 0; i <= 6; i++) {
            $('#calendar-textual-day').append('<div class="day-textual-calendar d-block d-md-none">'+appCalendar.dayData[i][0]+'</div>');
        }

    },

    updateTask: function(task, day) {

        var idTask = $(task).attr('id');
        var idDay = $(day).data('day');

        $.ajax({
                url: routeUpdateTask,
                method:'POST',
                data:{
                    'idTask':idTask,
                    'idDay':idDay,
                }
            }
        ).done(function(data) {
        }
        ).fail(function() {
            console.log('connexion failed');
        });

    },

    getTotalDayOfMonth: function(year, month) {
        return new Date(year, month+1, 0).getDate();
    },

    switchCalendar: function(evt) {

        appCalendar.clearCalendar();
        if($(evt.target).hasClass('fa-chevron-right')) {

            if(appCalendar.currentMonth == 11){
                appCalendar.generate(0, appCalendar.currentYear+1);
            }
            else {
                appCalendar.generate(appCalendar.currentMonth + 1, appCalendar.currentYear);
            }
        }
        else if($(evt.target).hasClass('fa-chevron-left')) {

            if(appCalendar.currentMonth == 0){
                appCalendar.generate(11, appCalendar.currentYear-1);
            }
            else {
                appCalendar.generate(appCalendar.currentMonth -1, appCalendar.currentYear);
            }
        }
    },

    clearCalendar: function(){

        $('.title-calendar').empty();
        $('#calendar-textual-day').empty();
        $('#calendar-container').empty();
    }
}

$(appCalendar.init);
