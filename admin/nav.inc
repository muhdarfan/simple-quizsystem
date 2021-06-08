<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/">UTHM</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active navbar-expand-lg">
                <a class="nav-link" href="admin.php">Admin<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active navbar-expand-lg">
                <a class="nav-link" href="lecturer.php">Lecturer</a>
            </li>
            <li class="nav-item active navbar-expand-lg">
                <a class="nav-link" href="students.php">Student</a>
            </li>
            <li class="nav-item dropdown active navbar-expand-lg">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Subject
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="subject.php">Subject</a>
                    <a class="dropdown-item" href="workload.php">Workload</a>
                </div>
            </li>
        </ul>

        <div class="navbar-nav">
            <a class="nav-link" href="../logout.php">Sign out</a>
        </div>
    </div>
</nav>
