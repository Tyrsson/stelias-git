// Adjust the height of the content to maintain 100% full Height
//jQuery(document).ready(setMinHeights);
jQuery(document).ready(sizeContent);
jQuery(window).resize(sizeContent);
function sizeContent() {
	var htmlHeight = jQuery("html").height();
	//var headerHeight = jQuery("#header").outerHeight(true);
	//var masterContainerHeight = jQuery("#master-container").outerHeight(true);
	//var curWrapperHeight = jQuery("#wrapper").outerHeight(true);
	//var footerHeight = jQuery("#footer").outerHeight(true);
	//var totalHeight = headerHeight + masterContainerHeight + footerHeight;
	//var heightDifference = htmlHeight - totalHeight;
	//var addToMasterContainer = heightDifference - footerHeight;
	//var newMasterContainerHeight = masterContainerHeight + addToMasterContainer;

	//var temp = headerHeight + masterContainerHeight + footerHeight;
	
	//jQuery("#master-container").css("height", newMasterContainerHeight);
	jQuery("#wrapper").css("height", htmlHeight);
}
//function setMinHeights() {
//	
//	var htmlHeight = jQuery("html").height() - 5;
//	var headerHeight = jQuery("#header").outerHeight(true);
//	var masterContainerHeight = jQuery("#master-container").outerHeight(true);
//	//var curWrapperHeight = jQuery("#wrapper").outerHeight(true);
//	var footerHeight = jQuery("#footer").outerHeight(true);
//	var totalHeight = headerHeight + masterContainerHeight + footerHeight;
//	var heightDifference = htmlHeight - totalHeight;
//	var addToMasterContainer = heightDifference - footerHeight;
//	var newMasterContainerHeight = masterContainerHeight + addToMasterContainer;
//
//	jQuery("#master-container").css("height", newMasterContainerHeight);
//	//jQuery("#wrapper").css("height", htmlHeight);
//}




