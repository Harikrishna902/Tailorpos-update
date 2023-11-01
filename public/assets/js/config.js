"use strict";
var primary = localStorage.getItem("primary") || '#24695c';
var secondary = localStorage.getItem("secondary") || '#ba895d';

window.vihoAdminConfig = {
	// Theme Primary Color
	primary: primary,
	// theme secondary color
	secondary: secondary,
};
// defalt layout
$('#default-demo').on('click', function (e) { 
    "use strict";
    localStorage.setItem('page-wrapper', 'page-wrapper compact-wrapper');
    localStorage.setItem('page-body-wrapper', 'sidebar-icon');
});
// compact layout
$('#compact-demo').on('click', function (e) { 
    "use strict";
    localStorage.setItem('page-wrapper', 'page-wrapper compact-wrapper compact-sidebar');
    localStorage.setItem('page-body-wrapper', 'sidebar-icon');
});
// modern layout
$('#modern-demo').on('click', function (e) { 
    "use strict";
    localStorage.setItem('page-wrapper', 'page-wrapper compact-wrapper modern-sidebar');
    localStorage.setItem('page-body-wrapper', 'sidebar-icon');
});