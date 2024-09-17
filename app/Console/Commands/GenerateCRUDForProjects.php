<?php

namespace App\Console\Commands;

use App\Models\Menu;
use App\Models\Project;
use App\Models\Table;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GenerateCRUDForProjects extends Command
{
    protected $signature = 'generate:crud';
    protected $description = 'Generate CRUD files for all projects';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $project = Project::where('id', 1)->first();
        $proName = $project->name;
        $directory = './curdGenerate/' . $proName;
        $this->installLaravel($proName);
        $this->installAuthUi($proName);
        $this->addLayout($directory,$proName);
        $this->adminLayout($directory,$proName);
        $this->addCustomCss($directory);
        $this->addMainJs($directory);
        $this->menuFile($directory,$proName);

        $menus = Menu::where('project_id', $project->id)->orderBy('sort_order')->get();

        foreach ($menus as $menu) {

            $fields = Table::where('menu_id', $menu->id)->orderBy('sort_order')->get();

            $modelName = ucfirst($menu->model_name);
            $directory = './curdGenerate/' . $proName;

            $this->generateCrudFiles($modelName, $fields, $directory);

            $this->info("CRUD generated for project: {$project->name}, menu: {$menu->model_name}");
            
        }

        $this->info('CRUD generation completed.');
    }

    public function installLaravel($proName)
    {

        $directory = './curdGenerate/' . $proName;

        if (File::exists($directory)) {
            File::deleteDirectory($directory);
            $this->info("Existing directory deleted: $directory");
        }

        $this->info("Installing Laravel in the root directory...");

        $process = new Process(['composer', 'create-project', 'laravel/laravel', $directory]);
        $process->setTimeout(3600); // Set timeout to 1 hour

        try {
            $process->mustRun();
            $this->info('Laravel installed successfully in the root directory!');
        } catch (ProcessFailedException $exception) {
            $this->error('Installation failed: ' . $exception->getMessage());
            return 1;
        }

        return 0;

    }

  
    public function installAuthUi($proName)
    {
        $directory = './curdGenerate/' . $proName;
    
        $this->info("Installing Laravel UI in the directory: {$directory}");
    
        // Step 1: Install Laravel UI using composer
        $composerProcess = new Process(['composer', 'require', 'laravel/ui']);
        $composerProcess->setWorkingDirectory($directory);
        $composerProcess->setTimeout(3600); // Set timeout to 1 hour
    
        try {
            $composerProcess->mustRun();
            $this->info('Laravel UI installed successfully!');
        } catch (ProcessFailedException $exception) {
            $this->error('Installation of Laravel UI failed: ' . $exception->getMessage());
            return 1;
        }
    
        // Step 2: Run artisan command to scaffold UI with authentication
        $artisanProcess = new Process(['php', 'artisan', 'ui', 'bootstrap', '--auth']);
        $artisanProcess->setWorkingDirectory($directory);
        $artisanProcess->setTimeout(3600); // Set timeout to 1 hour
    
        try {
            $artisanProcess->mustRun();
            $this->info('UI scaffolding with authentication installed successfully!');
        } catch (ProcessFailedException $exception) {
            $this->error('UI scaffolding failed: ' . $exception->getMessage());
            return 1;
        }
    
        return 0;
    }
    

    protected function generateCrudFiles($modelName, $fields, $directory)
    {
        $this->generateViews($modelName, $fields, $directory);
        $this->createStoreRequest($modelName, $fields, $directory);
        $this->createUpdateRequest($modelName, $fields, $directory);

        $this->generateController($modelName, $fields, $directory);
        $this->generateRoute($modelName, $directory);
        $this->generateModel($modelName, $fields, $directory);
        $this->generateMigration($modelName, $fields, $directory);
    }

    // Generate the controller
    protected function generateController($name, $fields, $directory)
    {
        $controllerPath = $directory . '/app/Http/Controllers';
        $controllerFilePath = $controllerPath . '/' . $name . 'Controller.php';

        // Ensure the directory exists
        File::makeDirectory($controllerPath, 0755, true, true);

        $controllerContent = $this->getControllerStub($name, $fields);
        File::put($controllerFilePath, $controllerContent);

        $this->info("Controller file created/updated: " . $name . 'Controller.php');
    }

    protected function createStoreRequest($modelName, $fields,$directory)
{

    $requestPath = $directory . '/app/Http/Requests';
    
    // Ensure the directory exists
    if (!File::exists($requestPath)) {
        File::makeDirectory($requestPath, 0755, true, true);
    }

    $rules = '';
    foreach ($fields as $field => $details) {
        if ($details->is_required == 1) {
            $rules .= "'{$details->field_name}' => 'required',\n";
        }
    }

    $storeRequestStub = <<<PHP
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\\$modelName;

class Store{$modelName}Request extends FormRequest
{
    public function authorize()
    {
        return true; // Set authorization logic if required
    }

    public function rules()
    {
        return [
            $rules
        ];
    }
}
PHP;

        File::put($requestPath.'/Store'.$modelName.'Request.php', $storeRequestStub);

}


protected function createUpdateRequest($modelName, $fields,$directory)
{
    $requestPath = $directory . '/app/Http/Requests';
    
    // Ensure the directory exists
    if (!File::exists($requestPath)) {
        File::makeDirectory($requestPath, 0755, true, true);
    }

    $rules = '';
    foreach ($fields as $field => $details) {
        if ($details->is_required == 1) {
            $rules .= "'{$details->field_name}' => 'required',\n";
        }
    }

    $updateRequestStub = <<<PHP
<?php

namespace App\Http\Requests;

use App\Models\\$modelName;
use Illuminate\Foundation\Http\FormRequest;

class Update{$modelName}Request extends FormRequest
{
    public function authorize()
    {
        return true; // Set authorization logic if required
    }

    public function rules()
    {
        return [
            $rules
        ];
    }
}
PHP;
        File::put($requestPath.'/Update'.$modelName.'Request.php', $updateRequestStub);

}

    protected function getControllerStub($name, $fields)
    {
        $modelName = ucfirst($name);
        $lowermodelName = strtolower($name);
        $fieldAssignments = '';
        foreach ($fields as $field) {
            if ($field->is_required == 1) {
                $fieldAssignments .= "            '{$field->field_name}' => 'required',\n";
            }
        }

        $controllerStub = <<<PHP
<?php

namespace App\Http\Controllers;

use App\Models\\$modelName;
use Illuminate\Http\Request;
use App\Http\Requests\Update{$modelName}Request;
use App\Http\Requests\Store{$modelName}Request;

class {$modelName}Controller extends Controller
{
    public function index()
    {
        \${$lowermodelName} = $modelName::all();
        return view('{$lowermodelName}.index', compact('{$lowermodelName}'));
    }

    public function create()
    {
        return view('{$lowermodelName}.create');
    }

    public function store(Store{$modelName}Request \$request)
    {

        $modelName::create(\$request->all());
        return redirect()->route('{$lowermodelName}.index');
    }

    public function show(\$id)
    {
        \${$lowermodelName} = $modelName::find(\$id);
        return view('{$lowermodelName}.show', compact('{$lowermodelName}'));
    }

    public function edit(\$id)
    {
        \${$lowermodelName} = $modelName::find(\$id);
        return view('{$lowermodelName}.edit', compact('{$lowermodelName}'));
    }

    public function update(Update{$modelName}Request \$request, \$id)
    {
        \${$lowermodelName} = $modelName::find(\$id);
        \${$lowermodelName}->update(\$request->all());
        return redirect()->route('{$lowermodelName}.index');
    }

    public function destroy(\$id)
    {
        $modelName::destroy(\$id);
        return redirect()->route('{$lowermodelName}.index');
    }
}
PHP;

        return $controllerStub;
    }
protected function generateRoute($modelName, $directory)
{
    $controllerNamespace = "App\\Http\\Controllers\\{$modelName}Controller";
    $routeContent = "Route::resource('" . strtolower($modelName) . "', {$controllerNamespace}::class);\n";
    $routeFilePath = $directory . '/routes/web.php';

    File::append($routeFilePath, $routeContent);
    $this->info("Route for {$modelName} added.");
}


protected function generateModel($modelName, $fields, $directory)
{
    $modelPath = $directory . '/app/Models/' . $modelName . '.php';

    // Collect the fillable fields
    $fillableFields = $fields->pluck('field_name')->map(fn($field) => "'$field'")->implode(', ');

    // Collect the constants for select and radio fields
    $selectConstants = $fields->filter(function ($field) {
        // Handle both 'select' and 'radio' field types
        return in_array($field->field_type, ['select', 'radio']);
    })->map(function ($field) {
        $keyValuePairs = json_decode($field->key_value_set, true);

        // Fallback to an empty array if decoding fails
        if (!is_array($keyValuePairs)) {
            $keyValuePairs = [];
        }

        // Prepare constant name based on the field name and type
        $constantName = strtoupper($field->field_name) . '_'. strtoupper($field->field_type) . '_OPTIONS';
        
        // Build key-value pairs for the constant
        $keyValuePairsString = implode(",\n            ", array_map(
            fn($key, $value) => "'$key' => '$value'",
            array_keys($keyValuePairs),
            $keyValuePairs
        ));

        return "public const $constantName = [\n            $keyValuePairsString\n        ];";
    })->implode("\n\n");

    // Model template
    $modelTemplate = <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class $modelName extends Model
{
    use HasFactory;

    protected \$fillable = [
        $fillableFields
    ];

    $selectConstants
}
PHP;

    // Ensure the directory exists
    File::makeDirectory(dirname($modelPath), 0755, true, true);
    File::put($modelPath, $modelTemplate);

    $this->info("Generated: {$modelPath}");
}




    protected function generateMigration($modelName, $fields, $directory)
    {
        $tableName = Str::snake(Str::plural($modelName));
        $migrationName = 'create_' . $tableName . '_table';
        $timestamp = now()->format('Y_m_d_His');

        // Migration template
        $migrationTemplate = <<<'PHP'
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class {{ $migrationName }} extends Migration
    {
        public function up()
        {
            Schema::create('{{ $tableName }}', function (Blueprint $table) {
                $table->id();
                {{ $columns }}
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('{{ $tableName }}');
        }
    }
    PHP;

    $columns = $fields->map(function ($field) {

        $isRequired = $field->is_required == 1 ? '' : '->nullable()';
    
        if ($field->field_type == 'text') {
            return "\$table->string('{$field->field_name}'){$isRequired};";
        } elseif ($field->field_type == 'textarea') {
            return "\$table->longText('{$field->field_name}'){$isRequired};";
        }
         elseif ($field->field_type == 'integer') {
          return "\$table->integer('{$field->field_name}'){$isRequired};";
         }elseif ($field->field_type == 'float') {
            return "\$table->float('{$field->field_name}', 15, 2){$isRequired};";
           }
           elseif ($field->field_type == 'decimal') {
            return "\$table->decimal('{$field->field_name}', 15, 2){$isRequired};";
           }
          elseif ($field->field_type == 'checkbox') {
             return "\$table->boolean('{$field->field_name}'){$isRequired};";
         } elseif ($field->field_type == 'select') {
            return "\$table->string('{$field->field_name}'){$isRequired};";
        }elseif ($field->field_type == 'radio') {
            return "\$table->string('{$field->field_name}'){$isRequired};";
        }elseif ($field->field_type == 'email') {
            return "\$table->string('{$field->field_name}'){$isRequired};";
        }elseif ($field->field_type == 'date') {
            return "\$table->date('{$field->field_name}'){$isRequired};";
        }elseif ($field->field_type == 'date_time') {
            return "\$table->datetime('{$field->field_name}'){$isRequired};";
        }elseif ($field->field_type == 'time') {
            return "\$table->time('{$field->field_name}'){$isRequired};";
        }
    
        return '';
    })->filter()->implode("\n");
    

        $migrationContent = str_replace(
            ['{{ $migrationName }}', '{{ $tableName }}', '{{ $columns }}'],
            [Str::studly($migrationName), $tableName, $columns],
            $migrationTemplate
        );

        $migrationPath = $directory . "/database/migrations/{$timestamp}_{$migrationName}.php";

        // Ensure the directory exists
        File::makeDirectory(dirname($migrationPath), 0755, true, true);
        File::put($migrationPath, $migrationContent);

        $this->info("Generated: {$migrationPath}");
    }

    protected function generateViews($name, $fields, $directory)
    {
        $viewsPath = $directory . '/resources/views/' . strtolower($name);

        // Ensure the directory exists
        File::makeDirectory($viewsPath, 0755, true, true);

        $views = ['create', 'edit', 'index', 'show'];
        foreach ($views as $view) {
            $viewContent = '';
            if ($view == 'index') {
                $viewContent = $this->indexFile($view, $fields, $name);
            } elseif ($view == 'create') {
                $viewContent = $this->createFile($view, $fields, $name);
            } elseif ($view == 'edit') {
                $viewContent = $this->editFile($view, $fields, $name);
            } else {
                $viewContent = $this->showFile($view, $fields, $name);
            }

            File::put($viewsPath . '/' . $view . '.blade.php', $viewContent);
        }

        $this->info("Views for $name created successfully.");
    }

    protected function indexFile($name, $fields, $modelName)
    {
        $modelName = strtolower($modelName);
    
        // Generate table headers
        $tableHeaders = '';
        foreach ($fields as $field => $details) {
            if ($details->in_list == 1) {
                $tableHeaders .= "<th>" . e(ucfirst($details->field_title)) . "</th>\n";
            }
        }
    
        // Generate table rows
        $tableRows = '';
        foreach ($fields as $field => $details) {
            if ($details->in_list == 1) {
                $tableRows .= "<td>{{ e(\$item->{$details->field_name}) }}</td>\n";
            }
        }
    
        // Build the view stub
        $viewStub = <<<HTML
    @extends('layouts.admin')
    
    @section('content')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a href="{{ route('{$modelName}.create') }}" class="btn btn-primary">Create New</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h1>{{ ucfirst('$modelName') }} List</h1>
            </div>
    
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            $tableHeaders
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (\${$modelName} as \$item)
                            <tr>
                                $tableRows
                                <td>
                                    <a href="{{ route('{$modelName}.edit', \$item->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('{$modelName}.destroy', \$item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
    HTML;
    
        return $viewStub;
    }
    

    protected function editFile($name, $fields, $modelName)
    {
        $modelNameLower = strtolower($modelName); // Lowercase model name for route generation
        $modelNameUpper = ucfirst($modelName);    // Uppercase model name for class references
    
        $formFields = '';
        foreach ($fields as $field => $details) {
            if ($details->in_edit == 1) {
                $formFields .= "<div class=\"form-group\">\n";
                $formFields .= "    <label for=\"{$details->field_name}\">" . ucfirst($details->field_title) . "</label>\n";
    
                // Handle text input fields
                if ($details->field_type === 'text') {
                    $formFields .= "    <input type=\"text\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}\">\n";
                }
                // Handle textarea fields
                elseif ($details->field_type === 'textarea') {
                    $formFields .= "    <textarea name=\"{$details->field_name}\" id=\"{$details->field_name}\" class=\"form-control\">{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}</textarea>\n";
                }
                // Handle select fields
                elseif ($details->field_type === 'select') {
                    $formFields .= "    <select name=\"{$details->field_name}\" id=\"{$details->field_name}\" class=\"form-control\">\n";
                    $formFields .= "        <option value disabled {{ old('{$details->field_name}', null) === null ? 'selected' : '' }}>Please select</option>\n";
                    $formFields .= "        @foreach(App\\Models\\{$modelNameUpper}::" . strtoupper($details->field_name) . "_SELECT_OPTIONS as \$key => \$label)\n";
                    $formFields .= "            <option value=\"{{ \$key }}\" {{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) === (string) \$key ? 'selected' : '' }}>{{ \$label }}</option>\n";
                    $formFields .= "        @endforeach\n";
                    $formFields .= "    </select>\n";
                }
                // Handle radio fields
                elseif ($details->field_type === 'radio') {
                    $formFields .= "    @foreach(App\\Models\\{$modelNameUpper}::" . strtoupper($details->field_name) . "_RADIO_OPTIONS as \$key => \$label)\n";
                    $formFields .= "        <div class=\"form-check {{ \$errors->has('{$details->field_name}') ? 'is-invalid' : '' }}\">\n";
                    $formFields .= "            <input class=\"form-check-input\" type=\"radio\" id=\"{$details->field_name}_{{ \$key }}\" name=\"{$details->field_name}\" value=\"{{ \$key }}\" {{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) === (string) \$key ? 'checked' : '' }}>\n";
                    $formFields .= "            <label class=\"form-check-label\" for=\"{$details->field_name}_{{ \$key }}\">{{ \$label }}</label>\n";
                    $formFields .= "        </div>\n";
                    $formFields .= "    @endforeach\n";
                }
                // Handle email fields
                elseif ($details->field_type === 'email') {
                    $formFields .= "    <input type=\"email\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}\">\n";
                }
                // Handle password fields
                elseif ($details->field_type === 'password') {
                    $formFields .= "    <input type=\"password\" name=\"{$details->field_name}\" class=\"form-control\">\n";
                }
                // Handle integer fields
                elseif ($details->field_type === 'integer') {
                    $formFields .= "    <input type=\"number\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}\" step=\"1\">\n";
                }
                // Handle money fields
                elseif ($details->field_type === 'money') {
                    $formFields .= "    <input type=\"number\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}\" step=\"0.01\">\n";
                }
                // Handle float fields
                elseif ($details->field_type === 'float') {
                    $formFields .= "    <input type=\"number\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}\" step=\"0.01\">\n";
                }
                // Handle date fields
                elseif ($details->field_type === 'date') {
                    $formFields .= "    <input type=\"text\" name=\"{$details->field_name}\" class=\"form-control date\" value=\"{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}\">\n";
                }
                // Handle date_time fields
                elseif ($details->field_type === 'date_time') {
                    $formFields .= "    <input type=\"text\" name=\"{$details->field_name}\" class=\"form-control datetime\" value=\"{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}\">\n";
                }
                // Handle time fields
                elseif ($details->field_type === 'time') {
                    $formFields .= "    <input type=\"text\" name=\"{$details->field_name}\" class=\"form-control timepicker\" value=\"{{ old('{$details->field_name}', \${$modelNameLower}->{$details->field_name}) }}\">\n";
                }
    
                $formFields .= "</div>\n";
            }
        }
    
        // View template
        $viewStub = <<<HTML
    @extends('layouts.admin')
    
    @section('content')
    <div class="card">
        <div class="card-header">
            Edit {$modelNameUpper}
        </div>
    
        <div class="card-body">
            <form action="{{ route('{$modelNameLower}.update', \${$modelNameLower}->id) }}" method="POST">
                @csrf
                @method('PUT')
                $formFields
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endsection
    HTML;
    
        return $viewStub;
    }
    

    protected function createFile($name, $fields, $modelName)
    {
        $modelNameLower = strtolower($modelName); // Use lowercase for routes
        $modelNameUpper = ucfirst($modelName);    // Capitalized for class references
    
        $formFields = '';
        foreach ($fields as $field => $details) {
            if ($details->in_create == 1) {
                $formFields .= "<div class=\"form-group\">\n";
                $formFields .= "    <label for=\"{$details->field_name}\">" . ucfirst($details->field_title) . "</label>\n";
    
                // Handle different field types
                if ($details->field_type === 'text') {
                    $formFields .= "    <input type=\"text\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}') }}\">\n";
                } elseif ($details->field_type === 'textarea') {
                    $formFields .= "<textarea name=\"{$details->field_name}\" id=\"{$details->field_name}\" class=\"form-control\">{{ old('{$details->field_name}') }}</textarea>\n";
                } elseif ($details->field_type === 'select') {
                    $formFields .= "<select name=\"{$details->field_name}\" id=\"{$details->field_name}\" class=\"form-control\">\n";
                    $formFields .= "    <option value disabled {{ old('{$details->field_name}', null) === null ? 'selected' : '' }}>Please select</option>\n";
                    $formFields .= "    @foreach(App\\Models\\{$modelNameUpper}::" . strtoupper($details->field_name) . "_SELECT_OPTIONS as \$key => \$label)\n";
                    $formFields .= "        <option value=\"{{ \$key }}\" {{ old('{$details->field_name}') === (string) \$key ? 'selected' : '' }}>{{ \$label }}</option>\n";
                    $formFields .= "    @endforeach\n";
                    $formFields .= "</select>\n";
                } elseif ($details->field_type === 'radio') {
                    $formFields .= "    @foreach(App\\Models\\{$modelNameUpper}::" . strtoupper($details->field_name) . "_RADIO_OPTIONS as \$key => \$label)\n";
                    $formFields .= "        <div class=\"form-check {{ \$errors->has('{$details->field_name}') ? 'is-invalid' : '' }}\">\n";
                    $formFields .= "            <input class=\"form-check-input\" type=\"radio\" id=\"{$details->field_name}_{{ \$key }}\" name=\"{$details->field_name}\" value=\"{{ \$key }}\" {{ old('{$details->field_name}', '') === (string) \$key ? 'checked' : '' }}>\n";
                    $formFields .= "            <label class=\"form-check-label\" for=\"{$details->field_name}_{{ \$key }}\">{{ \$label }}</label>\n";
                    $formFields .= "        </div>\n";
                    $formFields .= "    @endforeach\n";
                } elseif ($details->field_type === 'email') {
                    $formFields .= "    <input type=\"email\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}') }}\">\n";
                } elseif ($details->field_type === 'password') {
                    $formFields .= "    <input type=\"password\" name=\"{$details->field_name}\" class=\"form-control\">\n";
                } elseif ($details->field_type === 'integer') {
                    $formFields .= "    <input type=\"number\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}') }}\" step=\"1\">\n";
                } elseif ($details->field_type === 'money') {
                    $formFields .= "    <input type=\"number\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}') }}\" step=\"0.01\">\n";
                } elseif ($details->field_type === 'float') {
                    $formFields .= "    <input type=\"number\" name=\"{$details->field_name}\" class=\"form-control\" value=\"{{ old('{$details->field_name}') }}\" step=\"0.01\">\n";
                } elseif ($details->field_type === 'date') {
                    $formFields .= "    <input type=\"text\" name=\"{$details->field_name}\" class=\"form-control date\" value=\"{{ old('{$details->field_name}') }}\">\n";
                } elseif ($details->field_type === 'date_time') {
                    $formFields .= "    <input type=\"text\" name=\"{$details->field_name}\" class=\"form-control datetime\" value=\"{{ old('{$details->field_name}') }}\">\n";
                } elseif ($details->field_type === 'time') {
                    $formFields .= "    <input type=\"text\" name=\"{$details->field_name}\" class=\"form-control timepicker\" value=\"{{ old('{$details->field_name}') }}\">\n";
                }
    
                $formFields .= "</div>\n";
            }
        }
    
        // Form template
        $viewStub = <<<HTML
    @extends('layouts.admin')
    
    @section('content')
    <div class="card">
        <div class="card-header">
            Create {$modelNameUpper}
        </div>
    
        <div class="card-body">
            <form action="{{ route('{$modelNameLower}.store') }}" method="POST">
                @csrf
                $formFields
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endsection
    HTML;
    
        return $viewStub;
    }
    
    

    protected function showFile($name, $fields, $modelName)
    {

        $modelName = strtolower($modelName);
        $fieldRows = '';
        foreach ($fields as $field => $details) {

            $fieldRows .= "<tr>\n";
            $fieldRows .= "<th>\n";
            $fieldRows .= "    <td>$details->field_title</td>\n";

            $fieldRows .= "</th>\n";

            $fieldRows .= "    <td>{{ \${$name}->{$details->field_name} }}</td>\n";
            $fieldRows .= "</tr>\n";
        }

        $viewStub = <<<HTML

@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
    {$modelName} Details
    </div>
    <div class="form-group">
    
    <table class="table">
        <tbody>
            $fieldRows
        </tbody>
    </table>

    </div>
    </div>
</div>
@endsection
HTML;

        return $viewStub;
    }


    public function addLayout($directory,$name)
    {
        $layoutPath = $directory . '/resources/views/layouts';
    
        // Ensure the directory exists
        if (!File::exists($layoutPath)) {
            File::makeDirectory($layoutPath, 0755, true, true);
        }
    
        $appBladePath = $layoutPath . '/app.blade.php';
    
        // The content you want to add to app.blade.php
        $content = <<<HTML
    <!DOCTYPE html>
    <html>
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    
        <title>$name</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://unpkg.com/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
        <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
        @yield('styles')
    </head>
    
    <body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
        <div class="c-app flex-row align-items-center">
            <div class="container">
                @yield("content")
            </div>
        </div>
        @yield('scripts')
    </body>
    
    </html>
    HTML;
    
        // Create or replace the app.blade.php file
        File::put($appBladePath, $content);
    
        $this->info("Layout file app.blade.php created or updated at {$appBladePath}");
    }
    

    public function adminLayout($directory, $name)
    {
        $layoutPath = $directory . '/resources/views/layouts';
        $siteName = $name;

        if (!File::exists($layoutPath)) {
            File::makeDirectory($layoutPath, 0755, true, true);
        }
    
        $adminBladePath = $layoutPath . '/admin.blade.php';
    
        


        $templatePath = resource_path('views/layouts/template/admin.blade.php');

        // Check if the template file exists
        if (!File::exists($templatePath)) {
            $this->error('Template file does not exist: ' . $templatePath);
            return 1;
        }

        // Get the content from the template
        $content = File::get($templatePath);
        // Replace placeholder with actual site name
        $content = str_replace('$name', $siteName, $content);

        // Write the content to the file
        File::put($adminBladePath, $content);

        $this->info('admin.blade.php has been created or updated successfully.');


    }

    public function menuFile($directory,$name){

        $menus = Menu::with('parentMenus')->whereNull('parent_id')->orderBy('sort_order')->get();

        $partialsPath = $directory . '/resources/views/partials';

        if (!File::exists($partialsPath)) {
            File::makeDirectory($partialsPath, 0755, true, true);
        }
    
        $menuBladePath = $partialsPath . '/menu.blade.php';
    

        // Generate the HTML structure for the menu
        $menuHtml = $this->generateMenuHtml($menus,$name);


        // Write the generated HTML to the file
        File::put($menuBladePath, $menuHtml);

        $this->info('Dynamic menu generated and saved to menu.blade.php');

    }

    private function generateMenuHtml($menus,$name)
    {
        $html = '
    <div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">
        <div class="c-sidebar-brand d-md-down-none">
            <a class="c-sidebar-brand-full h4" href="#">
                ' . $name . '
            </a>
        </div>
        <ul class="c-sidebar-nav">
            <li class="c-sidebar-nav-item">
                <a href="{{ route("home") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>';
    
        // Loop through parent menus
        foreach ($menus as $menu) {
            $menuName = strtolower($menu->model_name);
            // If the menu has children, it's a dropdown
            if ($menu->parentMenus->isNotEmpty()) {
                $html .= '
            <li class="c-sidebar-nav-dropdown">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon"></i>
                    ' . e($menu->title) . '
                </a>
                <ul class="c-sidebar-nav-dropdown-items">';
    
                // Loop through child menus
                foreach ($menu->parentMenus as $childMenu) {
                    $childMenuName = strtolower($childMenu->model_name);
                    $html .= '
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("' . e($childMenuName) . '.index") }}" class="c-sidebar-nav-link">
                            <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon"></i>
                            ' . e($childMenu->title) . '
                        </a>
                    </li>';
                }
    
                $html .= '
                </ul>
            </li>';
            } else {
                // If no children, it's a single link
                $html .= '
            <li class="c-sidebar-nav-item">
                <a href="{{ route("' . e($menuName) . '.index") }}" class="c-sidebar-nav-link">
                    <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon"></i>
                    ' . e($menu->title) . '
                </a>
            </li>';
            }
        }
    
        // Add the logout item at the end
        $html .= '
            <li class="c-sidebar-nav-item">
                <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById(\'logoutform\').submit();">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>';
    
        $html .= '
        </ul>
    </div>';
    
        return $html;
    }


    public function addCustomCss($directory)
    {
        $cssFile = file_get_contents(base_path('public/template/custom.css'));
    
        $cssPath = $directory . '/public/css';
    
        if (!File::exists($cssPath)) {
            File::makeDirectory($cssPath, 0755, true, true);
        }
    
        $customCssPath = $cssPath . '/custom.css';
    
        File::put($customCssPath, $cssFile);
        $this->info('Css file added');

    }
    
    
    public function addMainJs($directory){
       
        $jsFile = file_get_contents(base_path('public/template/main.js'));
    
        $jsPath = $directory . '/public/js';
    
        if (!File::exists($jsPath)) {
            File::makeDirectory($jsPath, 0755, true, true);
        }
    
        $mainJsPath = $jsPath . '/main.js';
    
        File::put($mainJsPath, $jsFile);

        $this->info('Js file added');

    }

}
