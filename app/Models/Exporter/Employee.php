<?php

namespace App\Models\Exporter;

use App\Models\Exporter\Builders\EmployeeEloquentBuilder;
use App\Models\Exporter\Builders\TeacherEloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class Employee extends Model
{
    /**
     * @var string
     */
    protected $table = 'exporter_employee';

    /**
     * @var Collection
     */
    protected $alias;

    /**
     * @param Builder $query
     * @return TeacherEloquentBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new EmployeeEloquentBuilder($query);
    }

    /**
     * @return array
     */
    public function getExportedColumnsByGroup()
    {
        return [
            'Códigos' => [
                'id' => 'ID Pessoa',
                'school_id' => 'ID Escola',
            ],
            'Servidor' => [
                'name' => 'Nome',
                'social_name' => 'Nome social e/ou afetivo',
                'cpf' => 'CPF',
                'rg' => 'RG',
                'rg_issue_date' => 'RG (Data Emissão)',
                'rg_state_abbreviation' => 'RG (Estado)',
                'date_of_birth' => 'Data de nascimento',
                'email' => 'E-mail',
                'sus' => 'Número SUS',
                'nis' => 'NIS (PIS/PASEP)',
                'occupation' => 'Ocupação',
                'organization' => 'Empresa',
                'monthly_income' => 'Renda Mensal',
                'gender' => 'Gênero',
                'race' => 'Raça',
            ],
            'Alocação' => [
                'employee_workload' => 'Carga horária do servidor',
                'year' => 'Ano',
                'school' => 'Escola',
                'period' => 'Período',
                'role' => 'Função',
                'link' => 'Vínculo',
                'allocated_workload' => 'Carga horária alocada',
            ],
            'Informações' => [
                'phones.phones' => 'Telefones',
                'disabilities.disabilities' => 'Deficiências',
                'schooling_degree' => 'Escolaridade',
                'high_school_type' => 'Tipo de ensino médio cursado',
                'employee_postgraduates_complete' => 'Pós-Graduações concluídas',
                'continuing_education_course' => 'Outros cursos de formação continuada',
                'employee_graduation_complete' => 'Curso(s) superior(es) concluído(s)',
            ],
            'Endereço' => [
                'place.address' => 'Logradouro',
                'place.number' => 'Número',
                'place.complement' => 'Complemento',
                'place.neighborhood' => 'Bairro',
                'place.postal_code' => 'CEP',
                'place.latitude' => 'Latitude',
                'place.longitude' => 'Longitude',
                'place.city' => 'Cidade',
                'place.state_abbreviation' => 'Sigla do Estado',
                'place.state' => 'Estado',
                'place.country' => 'País',
            ],
        ];
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Servidores';
    }

    public function getDescription()
    {
        return 'Os dados exportados serão contabilizados por quantidade de servidores(as) alocados(as) no ano filtrado, agrupando as informações das alocações nas escolas.';
    }

    /**
     * @param string $column
     * @return string
     */
    public function alias($column)
    {
        if (empty($this->alias)) {
            $this->alias = collect($this->getExportedColumnsByGroup())->flatMap(function ($item) {
                return $item;
            });
        }

        return $this->alias->get($column, $column);
    }
}
