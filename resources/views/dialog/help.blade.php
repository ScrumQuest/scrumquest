<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">ScrumQuest help page</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    ScrumQuest is a project management tool that aims to guide starting software developers through the Scrum process.
                    Each user can create their own projects and can be assigned to work in teams for pre-defined projects.<br>
                    <br>

                </p>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingZero">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseZero" aria-expanded="true" aria-controls="collapseZero">
                                Basics
                            </button>
                        </h2>
                        <div id="collapseZero" class="accordion-collapse collapse" aria-labelledby="headingZero" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>
                                    A project consists of a backlog with backlog items. Backlog items define the step-by-step work that must be completed in order to complete the entire project.
                                    The backlog should be filled and maintained by the project members. Items can be created, modified and deleted. The project backlog is always identified by the <i class="bi bi-card-list"></i> icon.
                                    Backlog items are identified by three colors on the sprint planning view: green for on-track, yellow for late and blue for completed.<br>
                                    <br>
                                    A project also uses sprints. A sprint is a short development cycle of 1 to 4 weeks. On the first day of the sprint, the team plans the work for the upcoming sprint by dragging items from the backlog to the planning board.
                                    In ScrumQuest this process is done by going to the sprint that has the 'planning' status. From there items can be moved from the backlog into the sprint.<br>
                                    <br>
                                    A sprint continues until the first day of the next sprint, on which it can be marked as 'finished'. All the work done during the sprint can be shown in a demo to the client.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Planning
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>
                                    The planning process is the heart of ScrumQuest. On the planning board, all team members are listed in their own lane. The columns of the planning board represent the working days of the sprint where items can be planned.
                                    During the planning event on the first day of the sprint, team members take turns in picking relevant items from the project backlog and dragging them into the sprint.
                                    When a team member picks a task, they make a personal estimate on how long they think the task will take to complete (in days) and place it in the corresponding column.<br>
                                    <br>
                                    An example, if a member picks their first task, and they estimate it taking two days to implement, they will drag that item to Tuesday in the first week of the sprint. The second item that that member picks will be planned in the same way, but counting the number of days from the last planned item for that member.<br>
                                    <br>
                                    Planning continues until the sprint is reasonably full. When this is done, one team member clicks on the 'start sprint' button. At this point ScrumQuest will provide automated feedback on the plan.
                                    As a team, you can choose to take the feedback and correct the plan, or ignore the feedback and continue starting the sprint.<br>
                                    <br>
                                    In order to make the plan more accurate, each team member has the opportunity to mark days as 'non-project' days. This can happen for personal reasons, but also for planned educational moments that take more than half a day of time.<br>
                                    Marking days as (non-)project day can be done by using the <i class="bi bi-calendar2-x"></i> and <i class="bi bi-calendar2-check"></i> icons. Marking non-project days can only be done during planning.<br>
                                    <br>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Daily stand-up
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>
                                    After the sprint has been started the planning view will be slightly updated. A new feedback button is shown and the non-project days can no longer be updated.<br>
                                    <br>
                                    This is the view for the daily stand-up. The team gathers on a daily basis to discuss how their project is going. Taking turns each member discussed what they did the day before, what their plan for the day is and if they need any help.
                                    During the process, the opportunity may arise to update the plan. Items that are done, can be marked as such by moving the slider to the right. Items that should have been done, but aren't, can be replanned or reassigned by dragging them around in the sprint.
                                    This way the team ensures that their plan remains up-to-date and achievable.<br>
                                    <br>
                                    ScrumQuest also provides automated feedback on the current plan during this phase. By clicking on the feedback button in the planning view suggestions are shown on how the plan might be improved.
                                    Again, it is up to the team to adjust their plan based on the feedback, or ignore it and continue.<br>
                                    <br>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Finalizing the sprint
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>
                                    When the sprint is over, all the remaining, uncompleted, work items should be moved to the next sprint. This can be done by dragging them back to the backlog from the planning view.
                                    After this, the sprint can be finished and the team can move to planning the next sprint. Starting the process from the planning step again.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button title="Close" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
