YUI.add("gallery-torelativetime",function(B){function A(G,H){G=G||new Date();H=H||new Date();var I=(H.getTime()-G.getTime())/1000,J=A.strings,D="",K,E,C,F;if(arguments.length<2){K=(I<0)?J.fromnow:J.ago;}else{K=(I<0)?J.ahead:J.before;}if(I<0){E=G;G=H;H=E;I*=-1;}D=I<5?J.now:I<60?J.seconds:I<120?J.minute:I<3600?J.minutes.replace(/X/,Math.floor(I/60)):I<7200?J.hour:I<86400?J.hours.replace(/X/,Math.floor(I/3600)):I<172800?J.day:"";if(!D){G.setHours(0,0,0);H.setHours(0,0,0);I=Math.round((H.getTime()-G.getTime())/86400000);if(I>27){F=H.getFullYear()-G.getFullYear();C=H.getMonth()-G.getMonth();if(C<0){C+=12;F--;}if(C){D=(C>1)?J.months.replace(/X/,C):J.month;}if(F){if(C){D=J.and+D;}D=(F>1?J.years.replace(/X/,F):J.year)+D;}}if(!D){if(G.getDay()===H.getDay()){E=Math.round(I/7);D=(E>1)?J.weeks.replace(/X/,E):J.week;}else{D=J.days.replace(/X/,I);}}}if(D!==J.now){D+=K;}return D;}A.strings={now:"right now",seconds:"less than a minute",minute:"about a minute",minutes:"X minutes",hour:"about an hour",hours:"about X hours",day:"1 day",days:"X days",week:"about a week",weeks:"X weeks",month:"about a month",months:"X months",year:"about a year",years:"X years",ahead:" ahead",before:" before",ago:" ago",fromnow:" from now",and:" and "};B.toRelativeTime=A;},"gallery-2010.08.25-19-45");