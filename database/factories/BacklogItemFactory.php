<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BacklogItem>
 */
class BacklogItemFactory extends Factory
{
    private $userStories = [
        "User Registration" => "As a new user who wants to access the application's features, I want to create an account using my email and password. The registration process will include a form where I can input my email and password, and upon submission, the system will validate the information, send a verification email, and guide me through email verification to successfully create my account.",
        "Profile Management" => "As a registered user, I want to be able to edit my profile information. I should have the option to change my name, upload an avatar image, and update my contact details. The profile management section will include a user-friendly interface where I can easily make these changes, and the updated information will be stored in the database.",
        "Password Recovery" => "As a user who has forgotten my password, I want to initiate a password reset process. I'll click on the \"Forgot Password\" link, provide my registered email address, and the system will send a password reset email containing a unique link. Clicking on the link will take me to a secure page where I can set a new password and regain access to my account.",
        "Task Creation" => "As a project manager, I want to create tasks, assign them to team members, and set due dates. I'll access the \"Create Task\" feature, where I can enter the task details, assign a team member from a dropdown list, and set a due date using a date picker. The created task will then be displayed in the project task list with the assigned details.",
        "Task Status Update" => "As a team member, I want to update the status of a task. When viewing a task, I'll have the option to change its status by selecting from predefined statuses (e.g., to-do, in progress, completed). Upon selecting a status, the task card will visually reflect the update, and the system will store the new status in the database.",
        "Commenting on Tasks" => "As a collaborator, I want to leave comments on tasks to facilitate communication. I'll be able to access a task's details and add comments in a designated comment section. The comments will be timestamped, and other team members can view and respond to them, enabling effective collaboration.",
        "As a user, I want to upload files related to tasks and share them with team members. Within the task details view, there will be an \"Upload File\" button. Clicking this button will allow me to select and upload files from my device. The uploaded files will be associated with the task and accessible to authorized team members." => "File Upload and Sharing",
        "As a stakeholder, I want a dashboard that provides an overview of project progress and metrics. The dashboard will display visual elements like graphs and charts depicting task completion rates, project timeline, and team performance. It will be customizable to show key metrics that are relevant to different stakeholders." => "Project Dashboard",
        "As a user, I want to receive notifications for task assignments, updates, and mentions. Notifications will be sent via email and in-app alerts. When a task is assigned to me or updated, I'll receive an email and an in-app notification. Additionally, if I'm mentioned in a comment, I'll receive an immediate notification." => "Notifications",
        "As an administrator, I want to generate and export reports for project insights. The reporting feature will allow me to select date ranges, filter data, and choose report types (e.g., task completion, team productivity). The system will then generate downloadable reports in formats like PDF or CSV, providing valuable data for analysis." => "Reporting",
        "As a mobile user, I want the application to be responsive and functional on various devices. The application will be developed using responsive design principles, ensuring that its layout and features adapt seamlessly to different screen sizes. Mobile users will have a user-friendly experience whether accessing the app on smartphones or tablets." => "Mobile App Compatibility",
        "As a user, I want the ability to integrate the application with tools like Slack, Jira, or Trello. Integration options will be available in the user settings section. I'll be able to connect my accounts on these platforms and configure settings to enable cross-platform functionality. Integration will allow data sharing, notifications, and streamlined workflows between the application and the chosen third-party tools." => "Integration with Third-Party Tools",
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userStoryKey = array_rand($this->userStories);
        $project = Project::all()->random();
        return [
            'title' => $userStoryKey,
            'description' => $this->userStories[$userStoryKey],
            'assignee_id' => null,
            'sprint_id' => null,
            'week_in_sprint' => null,
            'day_in_week' => null,
            'project_id' => $project->id,
        ];
    }
}
