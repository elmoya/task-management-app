<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-panel {
            width: 30%;
            padding: 60px;
            background: #f7f7f7;
            border-right: 1px solid #ccc;
        }

        .right-panel {
            width: 70%;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .project {
            margin-bottom: 20px;
        }

        .project h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .task-list {
            margin-left: 20px;
        }

        .task-list li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: grab;
            background-color: #f9f9f9;
        }

        .task-list li.dragging {
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Left Panel -->
        <div class="left-panel">
            <h1>Task Manager</h1>

            <!-- Add Project Form -->
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <label for="projectName">Project Name</label>
                <input type="text" id="projectName" name="name" placeholder="Enter project name" required>
                <button type="submit">Add Project</button>
            </form>

            <hr />

            <!-- Add Task Form -->
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <label for="taskName">Task Name</label>
                <input type="text" id="taskName" name="name" placeholder="Enter task name" required>
                <select name="project_id" required>
                    <option value="">No Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                <button type="submit">Add Task</button>
            </form>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            @foreach ($projects as $project)
                <div class="project">
                    <h2>{{ $project->name }}</h2>
                    <ul class="task-list" data-project-id="{{ $project->id }}">
                        @foreach ($project->tasks->sortBy('priority') as $task)
                            <li draggable="true" data-task-id="{{ $task->id }}">
                                {{ $task->name }}

                                <button onclick="showEditForm({{ $task->id }}, '{{ $task->name }}')">Edit</button>

                                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <!-- Edit Task Modal (Hidden by Default) -->
        <div id="editTaskModal"
            style="display: none; position: fixed; top: 20%; left: 30%; padding: 20px; background-color: white; border: 1px solid black;">
            <form id="editTaskForm" method="POST">
                @csrf
                @method('PUT')
                <label for="taskName">Task Name:</label>
                <input type="text" id="taskName" name="name" required>
                <button type="submit">Update</button>
                <button type="button" onclick="hideEditForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.task-list').forEach(taskList => {
            let draggedItem = null;

            taskList.addEventListener('dragstart', (e) => {
                draggedItem = e.target;
                e.target.classList.add('dragging');
            });

            taskList.addEventListener('dragend', (e) => {
                e.target.classList.remove('dragging');
                draggedItem = null;
            });

            taskList.addEventListener('dragover', (e) => {
                e.preventDefault();
                const afterElement = getDragAfterElement(taskList, e.clientY);
                if (afterElement == null) {
                    taskList.appendChild(draggedItem);
                } else {
                    taskList.insertBefore(draggedItem, afterElement);
                }
            });

            taskList.addEventListener('drop', () => {
                const taskIds = Array.from(taskList.children).map(li => li.dataset.taskId);
                const projectId = taskList.dataset.projectId;

                // Send AJAX request to update priorities
                fetch(`/tasks/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        taskIds,
                        projectId
                    })
                }).then(response => {
                    if (response.ok) {
                        alert('Task priorities updated!');
                    } else {
                        alert('Failed to update task priorities.');
                    }
                });
            });
        });

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('[draggable]:not(.dragging)')];

            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return {
                        offset: offset,
                        element: child
                    };
                } else {
                    return closest;
                }
            }, {
                offset: Number.NEGATIVE_INFINITY
            }).element;
        }

        function showEditForm(taskId, taskName) {
            document.getElementById('editTaskForm').action = `/tasks/${taskId}`;
            document.getElementById('taskName').value = taskName;
            document.getElementById('editTaskModal').style.display = 'block';
        }

        function hideEditForm() {
            document.getElementById('editTaskModal').style.display = 'none';
        }
    </script>
</body>

</html>
