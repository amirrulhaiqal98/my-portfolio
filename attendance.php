<?php
class AttendanceSystem {
    private $studentsFile = 'students.json';
    private $attendanceFile = 'attendance.json';

    public function __construct() {
        if (!file_exists($this->studentsFile)) {
            file_put_contents($this->studentsFile, json_encode([]));
        }
        if (!file_exists($this->attendanceFile)) {
            file_put_contents($this->attendanceFile, json_encode([]));
        }
    }

    public function addStudent($studentId, $name) {
        $students = json_decode(file_get_contents($this->studentsFile), true);
        if (!isset($students[$studentId])) {
            $students[$studentId] = $name;
            file_put_contents($this->studentsFile, json_encode($students));
            return "Student $name added with ID $studentId";
        } else {
            return "Student with ID $studentId already exists";
        }
    }

    public function markAttendance($studentId, $date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        $students = json_decode(file_get_contents($this->studentsFile), true);
        $attendance = json_decode(file_get_contents($this->attendanceFile), true);

        if (isset($students[$studentId])) {
            if (!isset($attendance[$date])) {
                $attendance[$date] = [];
            }
            if (!in_array($studentId, $attendance[$date])) {
                $attendance[$date][] = $studentId;
                file_put_contents($this->attendanceFile, json_encode($attendance));
                return "Attendance marked for {$students[$studentId]} on $date";
            } else {
                return "Attendance already marked for {$students[$studentId]} on $date";
            }
        } else {
            return "Student with ID $studentId not found";
        }
    }

    public function viewAttendance($date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        $students = json_decode(file_get_contents($this->studentsFile), true);
        $attendance = json_decode(file_get_contents($this->attendanceFile), true);

        if (isset($attendance[$date])) {
            $output = "Attendance for $date:\n";
            foreach ($attendance[$date] as $studentId) {
                $output .= "- {$students[$studentId]} (ID: $studentId)\n";
            }
            return $output;
        } else {
            return "No attendance records for $date";
        }
    }
}

// Example usage
$system = new AttendanceSystem();

// Add students
echo $system->addStudent("001", "Alice") . "\n";
echo $system->addStudent("002", "Bob") . "\n";
echo $system->addStudent("003", "Charlie") . "\n";

// Mark attendance
echo $system->markAttendance("001") . "\n";
echo $system->markAttendance("002") . "\n";

// View attendance
echo $system->viewAttendance();
