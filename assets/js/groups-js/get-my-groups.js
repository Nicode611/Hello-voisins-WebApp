$.ajax({
    type: "GET",
    url: "../scripts/groups-scripts/show-my-groups.php",
    
    success: function(response) {

        groupsContainer = document.querySelector('.groups-container')
        groupsContainer.innerHTML = response

        groupOptions();
        startGroupDiscussion();
    }
});
