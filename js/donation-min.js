jQuery(document).ready(function(){loadTopDonations();loadLatestDonations()});(function(a){loadTopDonations=function(){loadDonations("top")}})(jQuery);(function(a){loadLatestDonations=function(){loadDonations("latest")}})(jQuery);(function(a){loadDonations=function(b){var d="#"+b+"donationslist";var f="#"+b+"loader";var c="#"+b+"error";var e="load-"+b+"Donations";a.post(Donation.ajaxurl,{action:e,pluginName:Donation.pluginName,type:b},function(g){if(g!==""){a(f).hide("slow");a(d).empty().html(g).show("slow")}else{a(f).hide("slow");a(c).show("slow")}},"html")}})(jQuery);