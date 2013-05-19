// moment.js language configuration
// language : icelandic (is)
// author : Hinrik Örn Sigurðsson : https://github.com/hinrik
(function(){function e(e){function a(e){return 11===e%100?!0:1===e%10?!1:!0}function t(e,t,n,_){var s=e+" ";switch(n){case"s":return t||_?"nokkrar sek\u00fandur":"nokkrum sek\u00fandum";case"m":return t?"m\u00edn\u00fata":"m\u00edn\u00fatu";case"mm":return a(e)?s+(t||_?"m\u00edn\u00fatur":"m\u00edn\u00fatum"):t?s+"m\u00edn\u00fata":s+"m\u00edn\u00fatu";case"hh":return a(e)?s+(t||_?"klukkustundir":"klukkustundum"):s+"klukkustund";case"d":return t?"dagur":_?"dag":"degi";case"dd":return a(e)?t?s+"dagar":s+(_?"daga":"d\u00f6gum"):t?s+"dagur":s+(_?"dag":"degi");case"M":return t?"m\u00e1nu\u00f0ur":_?"m\u00e1nu\u00f0":"m\u00e1nu\u00f0i";case"MM":return a(e)?t?s+"m\u00e1nu\u00f0ir":s+(_?"m\u00e1nu\u00f0i":"m\u00e1nu\u00f0um"):t?s+"m\u00e1nu\u00f0ur":s+(_?"m\u00e1nu\u00f0":"m\u00e1nu\u00f0i");case"y":return t||_?"\u00e1r":"\u00e1ri";case"yy":return a(e)?s+(t||_?"\u00e1r":"\u00e1rum"):s+(t||_?"\u00e1r":"\u00e1ri")}}e.lang("is",{months:"jan\u00faar_febr\u00faar_mars_apr\u00edl_ma\u00ed_j\u00fan\u00ed_j\u00fal\u00ed_\u00e1g\u00fast_september_okt\u00f3ber_n\u00f3vember_desember".split("_"),monthsShort:"jan_feb_mar_apr_ma\u00ed_j\u00fan_j\u00fal_\u00e1g\u00fa_sep_okt_n\u00f3v_des".split("_"),weekdays:"sunnudagur_m\u00e1nudagur_\u00feri\u00f0judagur_mi\u00f0vikudagur_fimmtudagur_f\u00f6studagur_laugardagur".split("_"),weekdaysShort:"sun_m\u00e1n_\u00feri_mi\u00f0_fim_f\u00f6s_lau".split("_"),weekdaysMin:"Su_M\u00e1_\u00der_Mi_Fi_F\u00f6_La".split("_"),longDateFormat:{LT:"H:mm",L:"DD/MM/YYYY",LL:"D. MMMM YYYY",LLL:"D. MMMM YYYY [kl.] LT",LLLL:"dddd, D. MMMM YYYY [kl.] LT"},calendar:{sameDay:"[\u00ed dag kl.] LT",nextDay:"[\u00e1 morgun kl.] LT",nextWeek:"dddd [kl.] LT",lastDay:"[\u00ed g\u00e6r kl.] LT",lastWeek:"[s\u00ed\u00f0asta] dddd [kl.] LT",sameElse:"L"},relativeTime:{future:"eftir %s",past:"fyrir %s s\u00ed\u00f0an",s:t,m:t,mm:t,h:"klukkustund",hh:t,d:t,dd:t,M:t,MM:t,y:t,yy:t},ordinal:"%d.",week:{dow:1,doy:4}})}"function"==typeof define&&define.amd&&define(["moment"],e),"undefined"!=typeof window&&window.moment&&e(window.moment)})();