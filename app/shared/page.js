/*global */

angular.module('wxApp').factory('page', function(){
  var title = 'default';
  var description = 'Darfield Personal Weather Station';
  return {
    title: function() { return title; },    
    setTitle: function(newTitle) { title = newTitle; },
    description: function() { return description; },
    setDescription: function(newDescription) { description = newDescription; }
  };
});