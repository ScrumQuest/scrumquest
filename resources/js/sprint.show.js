import './bootstrap';
import 'bootstrap/js/index.umd';
import Modal from "bootstrap/js/src/modal.js";
import axios from "axios";


const backlogModalElement = document.getElementById('showBacklog');
const modal = new Modal(backlogModalElement);

window.addEventListener('DOMContentLoaded', () => {
    const showBacklogButton = document.querySelector("#showBacklogButton");
    showBacklogButton.addEventListener('click', function (event) {
        modal.show();
    });

    const switches = document.querySelectorAll("[id^=done-switch-]");
    switches.forEach(function (switchElement) {
        switchElement.addEventListener('change', function (event) {
            const backlogItemId = switchElement.dataset.id;
            const projectId = switchElement.dataset.project;
            const action = switchElement.checked ? "markcomplete" : "markincomplete";

            try {
                axios.put(`/projects/${projectId}/backlogitems/${backlogItemId}/${action}`)
                    .then(function (response) {
                        const cardHeader = switchElement.closest(".card-header");
                        updateCardColor(cardHeader, response.data.completed, response.data.on_track);
                    });
            } catch (error) {
                console.error(error);
            }
        })
    });

    const startSprintButton = document.querySelector("#sprint-start-button");
    if(startSprintButton) {
        startSprintButton.addEventListener('click', function (event) {
            const feedbackList = document.querySelector("#sprint-feedback");
            feedbackList.innerHTML = "";

            try {
                const project = startSprintButton.dataset.project;
                const sprint = startSprintButton.dataset.sprint;

                axios.get(`/projects/${project}/sprints/${sprint}/feedback`).then(function (response) {
                    const feedbackItems = response.data;
                    feedbackItems.forEach(function (feedbackItem) {
                        const listElement = document.createElement("li");
                        listElement.innerHTML = feedbackItem;
                        feedbackList.appendChild(listElement);
                    });
                });
            } catch (error) {
                console.error(error);
            }
        });
    }

    const dailyFeedbackButton = document.querySelector("#dailyFeedbackButton");
    if(dailyFeedbackButton) {
        dailyFeedbackButton.addEventListener('click', function (event) {
            const feedbackList = document.querySelector("#daily-feedback");
            feedbackList.innerHTML = "";

            try {
                const project = dailyFeedbackButton.dataset.project;
                const sprint = dailyFeedbackButton.dataset.sprint;

                axios.get(`/projects/${project}/sprints/${sprint}/dailyfeedback`).then(function (response) {
                    const feedbackItems = response.data;
                    feedbackItems.forEach(function (feedbackItem) {
                        const listElement = document.createElement("li");
                        listElement.innerHTML = feedbackItem;
                        feedbackList.appendChild(listElement);
                    });

                    const dailyFeedbackCounter = document.querySelector('#dailyFeedbackCounter');
                    dailyFeedbackCounter.innerHTML = feedbackItems.length;
                });
            } catch (error) {
                console.error(error);
            }
        })
    }

    addDragNDropEventListeners(showBacklogButton);
});

function updateCardColor(cardHeader, completed, onTrack) {
    cardHeader.classList.remove("bg-primary", "bg-success", "bg-warning");
    if(completed) {
        cardHeader.classList.add("bg-success");
    } else if(onTrack) {
        cardHeader.classList.add("bg-primary");
    } else {
        cardHeader.classList.add("bg-warning");
    }
}

function addDragNDropEventListeners(showBacklogButton) {
    const draggables = document.querySelectorAll("[draggable='true']");
    draggables.forEach(function (draggable) {
        draggable.addEventListener('dragstart', (event) => {
            event.dataTransfer.setData("text/plain", event.target.closest("div[draggable='true']").id);

            showBacklogButton.classList.remove('btn-outline-primary', 'bi-card-list');
            showBacklogButton.classList.add('btn-danger', 'bi-trash');

            modal.hide();
        });
    });
    draggables.forEach(function (draggable) {
        draggable.addEventListener('dragend', (event) => {
            showBacklogButton.classList.remove('btn-danger', 'bi-trash');
            showBacklogButton.classList.add('btn-outline-primary', 'bi-card-list');
        });
    });

    showBacklogButton.addEventListener('dragover', (event) => {
        event.preventDefault();
    });
    showBacklogButton.addEventListener('drop', (event) => {
        unPlan(event);
    });

    const droppables = document.querySelectorAll(".droppable");
    droppables.forEach(function (droppable) {
        droppable.addEventListener('dragover', (event) => {
            event.preventDefault();
            if (event.target.classList.contains("droppable")) {
                event.target.classList.add("gray-background");
            }
        });
        droppable.addEventListener('dragleave', (event) => {
            event.preventDefault();
            if (event.target.classList.contains("droppable")) {
                event.target.classList.remove("gray-background");
            }
        });
        droppable.addEventListener('drop', (event) => {
            plan(event);
        })
    });
}


/**
 * This function handles the planning of a backlog item.
 * Planning can happen in two scenarios, a move within the sprint, or a move from the backlog to the sprint.
 *
 * @param event
 */
function plan(event) {
    event.preventDefault();
    const dropTarget = event.currentTarget;
    const data = event.dataTransfer.getData("text/plain");
    const draggedElement = document.querySelector(`#${data}`);
    dropTarget.classList.remove("gray-background");
    try {
        const assigneeId = dropTarget.dataset.user;
        const dayInWeek = dropTarget.dataset.day;
        const weekInSprint = dropTarget.dataset.week;
        const sprint = dropTarget.dataset.sprint;

        axios.put(`/projects/${dropTarget.dataset.project}/backlogitems/${draggedElement.dataset.id}/plan`, {
            assignee_id: assigneeId,
            day_in_week: dayInWeek,
            week_in_sprint: weekInSprint,
            sprint_id: sprint,
        }).then(function (response) {
            // Keep a reference to the parent element of the dragged element when it is dragged in from the backlog
            // so that it can be removed when the dragged element is at its new location.
            const dragOriginContainer = draggedElement.closest('div.backlog-container');

            if (dayInWeek === 'next' || dayInWeek === 'previous') {
                draggedElement.remove();
            } else {
                dropTarget.appendChild(draggedElement);
            }

            // Can be null if the item was moved within the sprint.
            if(dragOriginContainer != null) {
                dragOriginContainer.remove();
                modal.show();
            }

            draggedElement.querySelector('div.form-check').style.display = "";

            // When there are no more backlog items in the sidebar, show the appropriate message
            const unplannedItemContainer = document.querySelector("#unplannedItems");
            if(unplannedItemContainer.children.length === 0) {
                document.querySelector("#noBacklogItems").style.display = "";
            }

            const avatar = draggedElement.querySelector("img.avatar-link");
            avatar.src = response.data.avatar_link;
            avatar.style.display = "";
            draggedElement.querySelector("i").style.display = "none";

            const cardHeader = draggedElement.querySelector('.card-header');
            updateCardColor(cardHeader, response.data.completed, response.data.on_track);
        });
    } catch (error) {
        console.error(error);
    }
}

function unPlan(event) {
    event.preventDefault();
    const dropTarget = event.currentTarget;
    const data = event.dataTransfer.getData("text/plain");
    const draggedElement = document.querySelector(`#${data}`);
    dropTarget.classList.remove("gray-background");

    try {
        axios.put(`/projects/${dropTarget.dataset.project}/backlogitems/${draggedElement.dataset.id}/unplan`)
            .then(function (response) {
                const unplannedItems = document.querySelector("#unplannedItems");
                const colDiv = document.createElement('div');
                colDiv.classList.add('col-md-3', 'backlog-container');
                colDiv.appendChild(draggedElement);
                unplannedItems.appendChild(colDiv);

                const cardHeader = draggedElement.querySelector('.card-header');
                cardHeader.classList.remove('bg-success', 'bg-warning');
                cardHeader.classList.add('bg-primary');

                draggedElement.querySelector('div.form-check').style.display = "none";

                document.querySelector("#noBacklogItems").style.display = "none";

                const avatar = draggedElement.querySelector("img.avatar-link");
                avatar.src = "";
                avatar.style.display = "none";
                draggedElement.querySelector("i").style.display = "";
            });
    } catch (error) {
        console.error(error);
    }
}
