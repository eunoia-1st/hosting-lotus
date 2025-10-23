use App\Models\Admin;
use App\Models\Seat;
use App\Models\Customer;
use App\Models\Answer;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\Feedback;
use App\Models\FeedbackReview;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionOption;


App\Models\EmployeeShift::create(['employee_id' => 1,'shift_date' => '2025-07-29','start_time' => '09:00:00','end_time' => '17:00:00','shift_type' => 'morning',]);

DB::table('employee_shifts')->join('employees', 'employee_shifts.employee_id', '=', 'employees.id')->select('employee_shifts.shift_date','employee_shifts.start_time','employee_shifts.end_time','employee_shifts.shift_type','employees.name as employee_name','employees.position')->get();

$feedback = App\Models\Feedback::create(['customer_id' => 1,'seat_id' => 1,'submitted_at' => now()]);
$feedback = App\Models\Feedback::create(['customer_id' => 2,'seat_id' => 8,'submitted_at' => now()]);

$question = App\Models\Question::first();
$question->question_options()->attach([1, 2, 3, 4]);
$question->load('question_options');
$question->toArray();

Answer::create(['feedback_id' => 1,'question_id' => 1,'option_id' => 3,'answer_text' => null]);
Answer::create(['feedback_id' => 2,'question_id' => 2,'option_id' => null,'answer_text' => "Mantap Banget Restorannya"]);

$feedbacks = App\Models\Feedback::with(['customer:id,name','employees:id,name','answers.question:id,question_text','answers.question_option:id,question_value'])->get();

use Illuminate\Support\Facades\DB;$results = DB::table('employees')->join('employee_feedbacks', 'employees.id', '=', 'employee_feedbacks.employee_id')->join('feedbacks', 'feedbacks.id', '=', 'employee_feedbacks.feedback_id')->leftJoin('employee_shifts', 'employee_shifts.employee_id', '=', 'employees.id')->select('employees.name as employee_name','employee_shifts.shift_date','employee_shifts.start_time','employee_shifts.end_time','feedbacks.created_at as feedback_time')->whereBetween('feedbacks.created_at', [DB::raw('employee_shifts.start_time'), DB::raw('employee_shifts.end_time')])->get();