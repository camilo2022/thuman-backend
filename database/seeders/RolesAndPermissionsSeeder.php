<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'PersonnelRequisitionManagement', 'guard_name' => 'api', 'title' => 'Requisición de personal (Gestión)', 'description' => 'Gestión integral de la requisición de personal.']);
        Permission::create(['name' => 'PersonnelRequisitionManagement.Index', 'guard_name' => 'api', 'title' => 'Listado de requisiciones de personal', 'description' => 'Permite visualizar el listado general de requisiciones de personal.']);
        Permission::create(['name' => 'PersonnelRequisitionManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de requisición de personal', 'description' => 'Permite consultar el detalle de una requisición de personal específica.']);
        Permission::create(['name' => 'PersonnelRequisitionManagement.Store', 'guard_name' => 'api', 'title' => 'Creación de requisición de personal', 'description' => 'Permite crear nuevas requisiciones de personal dentro del sistema.']);
        Permission::create(['name' => 'PersonnelRequisitionManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de requisición de personal', 'description' => 'Permite modificar la información de una requisición de personal existente.']);
        Permission::create(['name' => 'PersonnelRequisitionManagement.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de requisición de personal', 'description' => 'Permite marcar una requisición de personal como rechazada.']);
        Permission::create(['name' => 'PersonnelRequisitionManagement.Published', 'guard_name' => 'api', 'title' => 'Publicación de requisición de personal', 'description' => 'Permite publicar y habilitar una requisición de personal para su gestión.']);
        Permission::create(['name' => 'PersonnelRequisitionManagement.Finished', 'guard_name' => 'api', 'title' => 'Finalización de requisición de personal', 'description' => 'Permite marcar una requisición de personal como finalizada.']);

        //================================================================================================================

        Role::create(['name' => 'Vacant', 'guard_name' => 'api', 'title' => 'Vacantes', 'description' => 'Administración de vacantes dentro del proceso de selección.']);
        Permission::create(['name' => 'Vacant.Index', 'guard_name' => 'api', 'title' => 'Listado de vacantes', 'description' => 'Permite visualizar el listado general de vacantes disponibles.']);
        Permission::create(['name' => 'Vacant.Candidate.Index', 'guard_name' => 'api', 'title' => 'Listado de candidatos', 'description' => 'Permite visualizar los candidatos asociados a una vacante.']);
        Permission::create(['name' => 'Vacant.Candidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de candidato', 'description' => 'Permite consultar el detalle de un candidato en una vacante.']);
        Permission::create(['name' => 'Vacant.Candidate.History', 'guard_name' => 'api', 'title' => 'Historial del candidato', 'description' => 'Permite consultar el historial del candidato dentro del proceso de selección.']);
        Permission::create(['name' => 'Vacant.Candidate.TestInitial.Find', 'guard_name' => 'api', 'title' => 'Consulta de prueba inicial', 'description' => 'Permite consultar la prueba inicial realizada por el candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewVideo.Find', 'guard_name' => 'api', 'title' => 'Consulta de entrevista en video', 'description' => 'Permite visualizar la entrevista en video del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewVideo.Store', 'guard_name' => 'api', 'title' => 'Registro de entrevista en video', 'description' => 'Permite registrar la entrevista en video del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.CriminalRecord.Find', 'guard_name' => 'api', 'title' => 'Consulta de antecedentes', 'description' => 'Permite consultar los antecedentes del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.CriminalRecord.Store', 'guard_name' => 'api', 'title' => 'Registro de antecedentes', 'description' => 'Permite registrar los antecedentes del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.CriminalRecord.Update', 'guard_name' => 'api', 'title' => 'Actualización de antecedentes', 'description' => 'Permite actualizar la información de antecedentes del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewInitial.Find', 'guard_name' => 'api', 'title' => 'Consulta de entrevista inicial', 'description' => 'Permite consultar la entrevista inicial del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewInitial.Store', 'guard_name' => 'api', 'title' => 'Registro de entrevista inicial', 'description' => 'Permite registrar la entrevista inicial del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewInitial.Update', 'guard_name' => 'api', 'title' => 'Actualización de entrevista inicial', 'description' => 'Permite actualizar la entrevista inicial del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewInitial.Reviewer', 'guard_name' => 'api', 'title' => 'Revisión de entrevista inicial', 'description' => 'Permite revisar y evaluar la entrevista inicial del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewInitial.Leader', 'guard_name' => 'api', 'title' => 'Aprobación líder entrevista inicial', 'description' => 'Permite al líder aprobar o rechazar la entrevista inicial.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewPsychotechnical.Find', 'guard_name' => 'api', 'title' => 'Consulta entrevista psicotécnica', 'description' => 'Permite consultar la entrevista psicotécnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewPsychotechnical.Store', 'guard_name' => 'api', 'title' => 'Registro entrevista psicotécnica', 'description' => 'Permite registrar la entrevista psicotécnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewPsychotechnical.Update', 'guard_name' => 'api', 'title' => 'Actualización entrevista psicotécnica', 'description' => 'Permite actualizar la entrevista psicotécnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.InterviewPsychotechnical.Psychologist', 'guard_name' => 'api', 'title' => 'Evaluación psicólogo', 'description' => 'Permite al psicólogo evaluar la entrevista psicotécnica.']);
        Permission::create(['name' => 'Vacant.Candidate.TestTechnical.Find', 'guard_name' => 'api', 'title' => 'Consulta prueba técnica', 'description' => 'Permite consultar la prueba técnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.TestTechnical.Store', 'guard_name' => 'api', 'title' => 'Registro prueba técnica', 'description' => 'Permite registrar la prueba técnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.TestTechnical.Update', 'guard_name' => 'api', 'title' => 'Actualización prueba técnica', 'description' => 'Permite actualizar la prueba técnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.TestTechnical.Leader', 'guard_name' => 'api', 'title' => 'Evaluación líder prueba técnica', 'description' => 'Permite al líder evaluar la prueba técnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.TestPsychotechnical.Find', 'guard_name' => 'api', 'title' => 'Consulta prueba psicotécnica', 'description' => 'Permite consultar la prueba psicotécnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.TestPsychotechnical.Store', 'guard_name' => 'api', 'title' => 'Registro prueba psicotécnica', 'description' => 'Permite registrar la prueba psicotécnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.TestPsychotechnical.Update', 'guard_name' => 'api', 'title' => 'Actualización prueba psicotécnica', 'description' => 'Permite actualizar la prueba psicotécnica del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.TestPsychotechnical.Psychologist', 'guard_name' => 'api', 'title' => 'Evaluación psicólogo prueba psicotécnica', 'description' => 'Permite al psicólogo evaluar la prueba psicotécnica.']);
        Permission::create(['name' => 'Vacant.Candidate.ExamMedical.Find', 'guard_name' => 'api', 'title' => 'Consulta examen médico', 'description' => 'Permite consultar el examen médico del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.ExamMedical.Store', 'guard_name' => 'api', 'title' => 'Registro examen médico', 'description' => 'Permite registrar el examen médico del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.ExamMedical.Update', 'guard_name' => 'api', 'title' => 'Actualización examen médico', 'description' => 'Permite actualizar el examen médico del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.ExamMedical.Reviewer', 'guard_name' => 'api', 'title' => 'Revisión examen médico', 'description' => 'Permite revisar y validar el examen médico del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.ExamPolygraph.Find', 'guard_name' => 'api', 'title' => 'Consulta examen de polígrafo', 'description' => 'Permite consultar el examen de polígrafo del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.ExamPolygraph.Store', 'guard_name' => 'api', 'title' => 'Registro examen de polígrafo', 'description' => 'Permite registrar el examen de polígrafo del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.ExamPolygraph.Update', 'guard_name' => 'api', 'title' => 'Actualización examen de polígrafo', 'description' => 'Permite actualizar el examen de polígrafo del candidato.']);
        Permission::create(['name' => 'Vacant.Candidate.ExamPolygraph.Reviewer', 'guard_name' => 'api', 'title' => 'Revisión examen de polígrafo', 'description' => 'Permite revisar y validar el examen de polígrafo del candidato.']);

        //================================================================================================================

        Role::create(['name' => 'ScheduleInterviewInitial', 'guard_name' => 'api', 'title' => 'Entrevista inicial (Gestión)', 'description' => 'Gestión integral del agendamiento y administración de entrevistas iniciales.']);
        Permission::create(['name' => 'ScheduleInterviewInitial.All', 'guard_name' => 'api', 'title' => 'Listado de entrevistas iniciales', 'description' => 'Permite visualizar el listado general de entrevistas iniciales programadas.']);
        Permission::create(['name' => 'ScheduleInterviewInitial.Find', 'guard_name' => 'api', 'title' => 'Consulta de entrevista inicial', 'description' => 'Permite consultar el detalle de una entrevista inicial específica.']);
        Permission::create(['name' => 'ScheduleInterviewInitial.Store', 'guard_name' => 'api', 'title' => 'Creación de entrevista inicial', 'description' => 'Permite programar nuevas entrevistas iniciales para candidatos.']);
        Permission::create(['name' => 'ScheduleInterviewInitial.Update', 'guard_name' => 'api', 'title' => 'Actualización de entrevista inicial', 'description' => 'Permite modificar la información de una entrevista inicial ya programada.']);

        //================================================================================================================

        Role::create(['name' => 'ScheduleInterviewPsychotechnical', 'guard_name' => 'api', 'title' => 'Entrevista psicotécnica (Gestión)', 'description' => 'Gestión integral del agendamiento y administración de entrevistas psicotécnicas.']);
        Permission::create(['name' => 'ScheduleInterviewPsychotechnical.All', 'guard_name' => 'api', 'title' => 'Listado de entrevistas psicotécnicas', 'description' => 'Permite visualizar el listado general de entrevistas psicotécnicas programadas.']);
        Permission::create(['name' => 'ScheduleInterviewPsychotechnical.Find', 'guard_name' => 'api', 'title' => 'Consulta de entrevista psicotécnica', 'description' => 'Permite consultar el detalle de una entrevista psicotécnica específica.']);
        Permission::create(['name' => 'ScheduleInterviewPsychotechnical.Store', 'guard_name' => 'api', 'title' => 'Creación de entrevista psicotécnica', 'description' => 'Permite programar nuevas entrevistas psicotécnicas para candidatos.']);
        Permission::create(['name' => 'ScheduleInterviewPsychotechnical.Update', 'guard_name' => 'api', 'title' => 'Actualización de entrevista psicotécnica', 'description' => 'Permite modificar la información de una entrevista psicotécnica ya programada.']);

        //================================================================================================================

        Role::create(['name' => 'ScheduleTechnicalTest', 'guard_name' => 'api', 'title' => 'Prueba técnica (Gestión)', 'description' => 'Gestión integral de la programación y aplicación de pruebas técnicas a candidatos.']);
        Permission::create(['name' => 'ScheduleTechnicalTest.All', 'guard_name' => 'api', 'title' => 'Listado de pruebas técnicas', 'description' => 'Permite visualizar el listado general de pruebas técnicas programadas.']);
        Permission::create(['name' => 'ScheduleTechnicalTest.Find', 'guard_name' => 'api', 'title' => 'Consulta de prueba técnica', 'description' => 'Permite consultar el detalle de una prueba técnica específica.']);
        Permission::create(['name' => 'ScheduleTechnicalTest.Store', 'guard_name' => 'api', 'title' => 'Creación de prueba técnica', 'description' => 'Permite programar nuevas pruebas técnicas para candidatos.']);
        Permission::create(['name' => 'ScheduleTechnicalTest.Update', 'guard_name' => 'api', 'title' => 'Actualización de prueba técnica', 'description' => 'Permite modificar la información de una prueba técnica ya programada.']);

        //================================================================================================================

        Role::create(['name' => 'SchedulePsychotechnicalTest', 'guard_name' => 'api', 'title' => 'Prueba psicotécnica (Gestión)', 'description' => 'Gestión integral de la programación y aplicación de pruebas psicotécnicas a candidatos.']);
        Permission::create(['name' => 'SchedulePsychotechnicalTest.All', 'guard_name' => 'api', 'title' => 'Listado de pruebas psicotécnicas', 'description' => 'Permite visualizar el listado general de pruebas psicotécnicas programadas.']);
        Permission::create(['name' => 'SchedulePsychotechnicalTest.Find', 'guard_name' => 'api', 'title' => 'Consulta de prueba psicotécnica', 'description' => 'Permite consultar el detalle de una prueba psicotécnica específica.']);
        Permission::create(['name' => 'SchedulePsychotechnicalTest.Store', 'guard_name' => 'api', 'title' => 'Creación de prueba psicotécnica', 'description' => 'Permite programar nuevas pruebas psicotécnicas para candidatos.']);
        Permission::create(['name' => 'SchedulePsychotechnicalTest.Update', 'guard_name' => 'api', 'title' => 'Actualización de prueba psicotécnica', 'description' => 'Permite modificar la información de una prueba psicotécnica ya programada.']);

        //================================================================================================================

        Role::create(['name' => 'ScheduleMedicalExam', 'guard_name' => 'api', 'title' => 'Examen médico (Gestión)', 'description' => 'Gestión integral de la programación y control de exámenes médicos para candidatos.']);
        Permission::create(['name' => 'ScheduleMedicalExam.All', 'guard_name' => 'api', 'title' => 'Listado de exámenes médicos', 'description' => 'Permite visualizar el listado general de exámenes médicos programados.']);
        Permission::create(['name' => 'ScheduleMedicalExam.Find', 'guard_name' => 'api', 'title' => 'Consulta de examen médico', 'description' => 'Permite consultar el detalle de un examen médico específico.']);
        Permission::create(['name' => 'ScheduleMedicalExam.Store', 'guard_name' => 'api', 'title' => 'Creación de examen médico', 'description' => 'Permite programar nuevos exámenes médicos para candidatos.']);
        Permission::create(['name' => 'ScheduleMedicalExam.Update', 'guard_name' => 'api', 'title' => 'Actualización de examen médico', 'description' => 'Permite modificar la información de un examen médico ya programado.']);

        //================================================================================================================

        Role::create(['name' => 'SchedulePolygraphExam', 'guard_name' => 'api', 'title' => 'Examen polígrafo (Gestión)', 'description' => 'Gestión integral de la programación y control de exámenes de polígrafo para candidatos.']);
        Permission::create(['name' => 'SchedulePolygraphExam.All', 'guard_name' => 'api', 'title' => 'Listado de exámenes de polígrafo', 'description' => 'Permite visualizar el listado general de exámenes de polígrafo programados.']);
        Permission::create(['name' => 'SchedulePolygraphExam.Find', 'guard_name' => 'api', 'title' => 'Consulta de examen de polígrafo', 'description' => 'Permite consultar el detalle de un examen de polígrafo específico.']);
        Permission::create(['name' => 'SchedulePolygraphExam.Store', 'guard_name' => 'api', 'title' => 'Creación de examen de polígrafo', 'description' => 'Permite programar nuevos exámenes de polígrafo para candidatos.']);
        Permission::create(['name' => 'SchedulePolygraphExam.Update', 'guard_name' => 'api', 'title' => 'Actualización de examen de polígrafo', 'description' => 'Permite modificar la información de un examen de polígrafo ya programado.']);

        //================================================================================================================

        Role::create(['name' => 'ContractManagement', 'guard_name' => 'api', 'title' => 'Gestión de contratos (Gestión)', 'description' => 'Administración del ciclo de vida de los contratos laborales, desde su creación hasta su finalización.']);
        Permission::create(['name' => 'ContractManagement.All', 'guard_name' => 'api', 'title' => 'Listado de contratos', 'description' => 'Permite visualizar el listado general de contratos registrados.']);
        Permission::create(['name' => 'ContractManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de contrato', 'description' => 'Permite consultar el detalle de un contrato específico.']);
        Permission::create(['name' => 'ContractManagement.Store', 'guard_name' => 'api', 'title' => 'Creación de contrato', 'description' => 'Permite registrar nuevos contratos en el sistema.']);
        Permission::create(['name' => 'ContractManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de contrato', 'description' => 'Permite modificar la información de un contrato existente.']);
        Permission::create(['name' => 'ContractManagement.Policies.All', 'guard_name' => 'api', 'title' => 'Listado de pólizas', 'description' => 'Permite visualizar el listado general de pólizas asociadas a contratos.']);
        Permission::create(['name' => 'ContractManagement.Policies.Find', 'guard_name' => 'api', 'title' => 'Consulta de póliza', 'description' => 'Permite consultar el detalle de una póliza específica.']);
        Permission::create(['name' => 'ContractManagement.Policies.Store', 'guard_name' => 'api', 'title' => 'Creación de póliza', 'description' => 'Permite registrar nuevas pólizas de contrato.']);
        Permission::create(['name' => 'ContractManagement.Policies.Update', 'guard_name' => 'api', 'title' => 'Actualización de póliza', 'description' => 'Permite modificar la información de una póliza existente.']);
        Permission::create(['name' => 'ContractManagement.Policies.Actived', 'guard_name' => 'api', 'title' => 'Activación de póliza', 'description' => 'Permite activar una póliza.']);
        Permission::create(['name' => 'ContractManagement.Policies.Canceled', 'guard_name' => 'api', 'title' => 'Cancelación de póliza', 'description' => 'Permite cancelar una póliza.']);
        Permission::create(['name' => 'ContractManagement.Policies.Expired', 'guard_name' => 'api', 'title' => 'Vencimiento de póliza', 'description' => 'Permite marcar una póliza como vencida.']);
        Permission::create(['name' => 'ContractManagement.Addendums.All', 'guard_name' => 'api', 'title' => 'Listado de otrosíes', 'description' => 'Permite visualizar el listado general de otrosíes asociados a contratos.']);
        Permission::create(['name' => 'ContractManagement.Addendums.Find', 'guard_name' => 'api', 'title' => 'Consulta de otrosí', 'description' => 'Permite consultar el detalle de un otrosí específico.']);
        Permission::create(['name' => 'ContractManagement.Addendums.Store', 'guard_name' => 'api', 'title' => 'Creación de otrosí', 'description' => 'Permite registrar nuevos otrosíes de contrato.']);
        Permission::create(['name' => 'ContractManagement.Addendums.Update', 'guard_name' => 'api', 'title' => 'Actualización de otrosí', 'description' => 'Permite modificar la información de un otrosí existente.']);

        //================================================================================================================

        Role::create(['name' => 'AffiliationManagement', 'guard_name' => 'api', 'title' => 'Gestión de afiliaciones (Gestión)', 'description' => 'Gestión de afiliaciones del personal a las entidades de seguridad social y beneficios.']);
        Permission::create(['name' => 'AffiliationManagement.All', 'guard_name' => 'api', 'title' => 'Listado de afiliaciones', 'description' => 'Permite visualizar el listado general de afiliaciones registradas.']);
        Permission::create(['name' => 'AffiliationManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de afiliación', 'description' => 'Permite consultar el detalle de una afiliación específica.']);
        Permission::create(['name' => 'AffiliationManagement.Store', 'guard_name' => 'api', 'title' => 'Creación de afiliación', 'description' => 'Permite registrar nuevas afiliaciones en el sistema.']);
        Permission::create(['name' => 'AffiliationManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de afiliación', 'description' => 'Permite modificar la información de una afiliación existente.']);

        //================================================================================================================

        Role::create(['name' => 'AbsenceManagement', 'guard_name' => 'api', 'title' => 'Permisos (Gestión)', 'description' => 'Gestión de permisos laborales del personal.']);
        Permission::create(['name' => 'AbsenceManagement.All', 'guard_name' => 'api', 'title' => 'Listado de permisos', 'description' => 'Permite visualizar todos los permisos según el alcance asignado.']);
        Permission::create(['name' => 'AbsenceManagement.All.Me', 'guard_name' => 'api', 'title' => 'Listado de mis permisos', 'description' => 'Permite visualizar únicamente los permisos propios.']);
        Permission::create(['name' => 'AbsenceManagement.All.Team', 'guard_name' => 'api', 'title' => 'Listado de permisos del equipo', 'description' => 'Permite visualizar los permisos del personal a cargo.']);
        Permission::create(['name' => 'AbsenceManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de permiso', 'description' => 'Permite consultar el detalle de un permiso específico.']);
        Permission::create(['name' => 'AbsenceManagement.Store', 'guard_name' => 'api', 'title' => 'Solicitud de permiso', 'description' => 'Permite registrar solicitudes de permisos laborales.']);
        Permission::create(['name' => 'AbsenceManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de permiso', 'description' => 'Permite modificar información de permisos existentes.']);

        //================================================================================================================

        Role::create(['name' => 'VacationManagement', 'guard_name' => 'api', 'title' => 'Vacaciones (Gestión)', 'description' => 'Gestión de solicitudes, aprobación y control de vacaciones del personal.']);
        Permission::create(['name' => 'VacationManagement.All', 'guard_name' => 'api', 'title' => 'Listado de vacaciones', 'description' => 'Permite visualizar todas las solicitudes de vacaciones según el alcance asignado.']);
        Permission::create(['name' => 'VacationManagement.All.Me', 'guard_name' => 'api', 'title' => 'Listado de mis vacaciones', 'description' => 'Permite visualizar únicamente las vacaciones propias.']);
        Permission::create(['name' => 'VacationManagement.All.Team', 'guard_name' => 'api', 'title' => 'Listado de vacaciones del equipo', 'description' => 'Permite visualizar las vacaciones del personal a cargo.']);
        Permission::create(['name' => 'VacationManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de vacaciones', 'description' => 'Permite consultar el detalle de una solicitud de vacaciones específica.']);
        Permission::create(['name' => 'VacationManagement.Store', 'guard_name' => 'api', 'title' => 'Solicitud de vacaciones', 'description' => 'Permite registrar solicitudes de vacaciones.']);
        Permission::create(['name' => 'VacationManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de vacaciones', 'description' => 'Permite modificar información de solicitudes existentes.']);

        //================================================================================================================

        Role::create(['name' => 'SeveranceManagement', 'guard_name' => 'api', 'title' => 'Cesantías (Gestión)', 'description' => 'Gestión de cesantías, traslados y consultas relacionadas al personal.']);
        Permission::create(['name' => 'SeveranceManagement.All', 'guard_name' => 'api', 'title' => 'Listado de cesantías', 'description' => 'Permite visualizar registros de cesantías según el alcance asignado.']);
        Permission::create(['name' => 'SeveranceManagement.All.Me', 'guard_name' => 'api', 'title' => 'Listado de mis cesantías', 'description' => 'Permite visualizar únicamente las cesantías propias.']);
        Permission::create(['name' => 'SeveranceManagement.All.Team', 'guard_name' => 'api', 'title' => 'Listado de cesantías del equipo', 'description' => 'Permite visualizar las cesantías del personal a cargo.']);
        Permission::create(['name' => 'SeveranceManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de cesantía', 'description' => 'Permite consultar el detalle de una cesantía específica.']);
        Permission::create(['name' => 'SeveranceManagement.Store', 'guard_name' => 'api', 'title' => 'Registro de cesantía', 'description' => 'Permite registrar movimientos o solicitudes de cesantías.']);
        Permission::create(['name' => 'SeveranceManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de cesantía', 'description' => 'Permite modificar información de cesantías existentes.']);

        //================================================================================================================

        Role::create(['name' => 'DisabilityManagement', 'guard_name' => 'api', 'title' => 'Incapacidades (Gestión)', 'description' => 'Gestión de incapacidades médicas y seguimiento de casos del personal.']);
        Permission::create(['name' => 'DisabilityManagement.All', 'guard_name' => 'api', 'title' => 'Listado de incapacidades', 'description' => 'Permite visualizar incapacidades según el alcance asignado.']);
        Permission::create(['name' => 'DisabilityManagement.All.Me', 'guard_name' => 'api', 'title' => 'Listado de mis incapacidades', 'description' => 'Permite visualizar únicamente las incapacidades propias.']);
        Permission::create(['name' => 'DisabilityManagement.All.Team', 'guard_name' => 'api', 'title' => 'Listado de incapacidades del equipo', 'description' => 'Permite visualizar incapacidades del personal a cargo.']);
        Permission::create(['name' => 'DisabilityManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de incapacidad', 'description' => 'Permite consultar el detalle de una incapacidad específica.']);
        Permission::create(['name' => 'DisabilityManagement.Store', 'guard_name' => 'api', 'title' => 'Registro de incapacidad', 'description' => 'Permite registrar incapacidades médicas.']);
        Permission::create(['name' => 'DisabilityManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de incapacidad', 'description' => 'Permite modificar información de incapacidades existentes.']);

        //================================================================================================================

        Role::create(['name' => 'LeaveManagement', 'guard_name' => 'api', 'title' => 'Licencias (Gestión)', 'description' => 'Gestión de licencias laborales conforme a la normativa vigente.']);
        Permission::create(['name' => 'LeaveManagement.All', 'guard_name' => 'api', 'title' => 'Listado de licencias', 'description' => 'Permite visualizar licencias según el alcance asignado.']);
        Permission::create(['name' => 'LeaveManagement.All.Me', 'guard_name' => 'api', 'title' => 'Listado de mis licencias', 'description' => 'Permite visualizar únicamente las licencias propias.']);
        Permission::create(['name' => 'LeaveManagement.All.Team', 'guard_name' => 'api', 'title' => 'Listado de licencias del equipo', 'description' => 'Permite visualizar licencias del personal a cargo.']);
        Permission::create(['name' => 'LeaveManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de licencia', 'description' => 'Permite consultar el detalle de una licencia específica.']);
        Permission::create(['name' => 'LeaveManagement.Store', 'guard_name' => 'api', 'title' => 'Solicitud de licencia', 'description' => 'Permite registrar solicitudes de licencias laborales.']);
        Permission::create(['name' => 'LeaveManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de licencia', 'description' => 'Permite modificar información de licencias existentes.']);

        //================================================================================================================

        Role::create(['name' => 'CertificateManagement', 'guard_name' => 'api', 'title' => 'Certificados (Gestión)', 'description' => 'Gestión y emisión de certificados laborales para el personal.']);
        Permission::create(['name' => 'CertificateManagement.All', 'guard_name' => 'api', 'title' => 'Listado de certificados', 'description' => 'Permite visualizar certificados según el alcance asignado.']);
        Permission::create(['name' => 'CertificateManagement.All.Me', 'guard_name' => 'api', 'title' => 'Listado de mis certificados', 'description' => 'Permite visualizar únicamente los certificados propios.']);
        Permission::create(['name' => 'CertificateManagement.All.Team', 'guard_name' => 'api', 'title' => 'Listado de certificados del equipo', 'description' => 'Permite visualizar certificados del personal a cargo.']);
        Permission::create(['name' => 'CertificateManagement.Find', 'guard_name' => 'api', 'title' => 'Consulta de certificado', 'description' => 'Permite consultar el detalle de un certificado específico.']);
        Permission::create(['name' => 'CertificateManagement.Store', 'guard_name' => 'api', 'title' => 'Generación de certificado', 'description' => 'Permite generar certificados laborales.']);
        Permission::create(['name' => 'CertificateManagement.Update', 'guard_name' => 'api', 'title' => 'Actualización de certificado', 'description' => 'Permite modificar información de certificados existentes.']);

        //================================================================================================================

        Role::create(['name' => 'PersonnelRequisitionValidate', 'guard_name' => 'api', 'title' => 'Requisición de personal (Validación)', 'description' => 'Validación y aprobación de requisiciones de personal.']);
        Permission::create(['name' => 'PersonnelRequisitionValidate.All', 'guard_name' => 'api', 'title' => 'Listado de requisiciones para validación', 'description' => 'Permite visualizar el listado de requisiciones de personal pendientes o disponibles para validación.']);
        Permission::create(['name' => 'PersonnelRequisitionValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de requisición para validación', 'description' => 'Permite consultar el detalle de una requisición de personal para su validación.']);
        Permission::create(['name' => 'PersonnelRequisitionValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de requisición', 'description' => 'Permite aprobar requisiciones de personal.']);
        Permission::create(['name' => 'PersonnelRequisitionValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de requisición', 'description' => 'Permite rechazar requisiciones de personal.']);

        //================================================================================================================

        Role::create(['name' => 'CandidateValidate', 'guard_name' => 'api', 'title' => 'Candidatos (Validación)', 'description' => 'Validación final del candidato para su contratación o rechazo dentro del proceso de selección.']);
        Permission::create(['name' => 'CandidateValidate.All', 'guard_name' => 'api', 'title' => 'Listado de candidatos para validación', 'description' => 'Permite visualizar el listado de candidatos disponibles para validación.']);
        Permission::create(['name' => 'CandidateValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de candidato', 'description' => 'Permite consultar el detalle de un candidato para validación.']);
        Permission::create(['name' => 'CandidateValidate.Hiring', 'guard_name' => 'api', 'title' => 'Contratación de candidato', 'description' => 'Permite aprobar y proceder a realizar contrato a un candidato del proceso de selección.']);
        Permission::create(['name' => 'CandidateValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de candidato', 'description' => 'Permite rechazar un candidato dentro del proceso de selección.']);

        //================================================================================================================

        Role::create(['name' => 'AffiliationValidate', 'guard_name' => 'api', 'title' => 'Afiliaciones (Validación)', 'description' => 'Validación de afiliaciones a seguridad social y beneficios.']);
        Permission::create(['name' => 'AffiliationValidate.All', 'guard_name' => 'api', 'title' => 'Listado de afiliaciones para validación', 'description' => 'Permite visualizar el listado de afiliaciones disponibles para validación.']);
        Permission::create(['name' => 'AffiliationValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de afiliación', 'description' => 'Permite consultar el detalle de una afiliación para validación.']);
        Permission::create(['name' => 'AffiliationValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de afiliación', 'description' => 'Permite aprobar una afiliación dentro del proceso de validación.']);
        Permission::create(['name' => 'AffiliationValidate.Refreshed', 'guard_name' => 'api', 'title' => 'Actualización de afiliación', 'description' => 'Permite solicitar la corrección de una afiliación cuando se detecta información errónea antes de su validación.']);

        //================================================================================================================

        Role::create(['name' => 'ContractValidate', 'guard_name' => 'api', 'title' => 'Gestión de contratos (Validación)', 'description' => 'Validación y aprobación de contratos laborales.']);
        Permission::create(['name' => 'ContractValidate.All', 'guard_name' => 'api', 'title' => 'Listado de contratos para validación', 'description' => 'Permite visualizar el listado de contratos disponibles para validación.']);
        Permission::create(['name' => 'ContractValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de contrato', 'description' => 'Permite consultar el detalle de un contrato para validación.']);
        Permission::create(['name' => 'ContractValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de contrato', 'description' => 'Permite aprobar un contrato laboral.']);
        Permission::create(['name' => 'ContractValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de contrato', 'description' => 'Permite rechazar un contrato laboral.']);
        Permission::create(['name' => 'ContractValidate.LegalRepresentativeSigned', 'guard_name' => 'api', 'title' => 'Firma de representante legal', 'description' => 'Permite registrar la firma del representante legal en un contrato.']);
        Permission::create(['name' => 'ContractValidate.LegalRepresentativeRejected', 'guard_name' => 'api', 'title' => 'Rechazo de representante legal', 'description' => 'Permite registrar el rechazo del contrato por parte del representante legal.']);

        //================================================================================================================

        Role::create(['name' => 'AbsenceValidate', 'guard_name' => 'api', 'title' => 'Permisos (Validación)', 'description' => 'Validación y aprobación de permisos laborales.']);
        Permission::create(['name' => 'AbsenceValidate.All', 'guard_name' => 'api', 'title' => 'Listado de permisos para validación', 'description' => 'Permite visualizar permisos disponibles para validación.']);
        Permission::create(['name' => 'AbsenceValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de permiso', 'description' => 'Permite consultar el detalle de un permiso para validación.']);
        Permission::create(['name' => 'AbsenceValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de permiso', 'description' => 'Permite aprobar permisos laborales.']);
        Permission::create(['name' => 'AbsenceValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de permiso', 'description' => 'Permite rechazar permisos laborales.']);

        //================================================================================================================

        Role::create(['name' => 'VacationValidate', 'guard_name' => 'api', 'title' => 'Vacaciones (Validación)', 'description' => 'Validación y aprobación de solicitudes de vacaciones.']);
        Permission::create(['name' => 'VacationValidate.All', 'guard_name' => 'api', 'title' => 'Listado de vacaciones para validación', 'description' => 'Permite visualizar solicitudes de vacaciones disponibles para validación.']);
        Permission::create(['name' => 'VacationValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de vacaciones', 'description' => 'Permite consultar el detalle de una solicitud de vacaciones.']);
        Permission::create(['name' => 'VacationValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de vacaciones', 'description' => 'Permite aprobar solicitudes de vacaciones.']);
        Permission::create(['name' => 'VacationValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de vacaciones', 'description' => 'Permite rechazar solicitudes de vacaciones.']);

        //================================================================================================================

        Role::create(['name' => 'SeveranceValidate', 'guard_name' => 'api', 'title' => 'Cesantías (Validación)', 'description' => 'Validación de solicitudes y procesos de cesantías.']);
        Permission::create(['name' => 'SeveranceValidate.All', 'guard_name' => 'api', 'title' => 'Listado de cesantías para validación', 'description' => 'Permite visualizar registros de cesantías disponibles para validación.']);
        Permission::create(['name' => 'SeveranceValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de cesantía', 'description' => 'Permite consultar el detalle de una cesantía.']);
        Permission::create(['name' => 'SeveranceValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de cesantía', 'description' => 'Permite aprobar procesos de cesantías.']);
        Permission::create(['name' => 'SeveranceValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de cesantía', 'description' => 'Permite rechazar procesos de cesantías.']);

        //================================================================================================================

        Role::create(['name' => 'DisabilityValidate', 'guard_name' => 'api', 'title' => 'Incapacidades (Validación)', 'description' => 'Validación de incapacidades médicas del personal.']);
        Permission::create(['name' => 'DisabilityValidate.All', 'guard_name' => 'api', 'title' => 'Listado de incapacidades para validación', 'description' => 'Permite visualizar incapacidades disponibles para validación.']);
        Permission::create(['name' => 'DisabilityValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de incapacidad', 'description' => 'Permite consultar el detalle de una incapacidad.']);
        Permission::create(['name' => 'DisabilityValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de incapacidad', 'description' => 'Permite aprobar incapacidades médicas.']);
        Permission::create(['name' => 'DisabilityValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de incapacidad', 'description' => 'Permite rechazar incapacidades médicas.']);

        //================================================================================================================

        Role::create(['name' => 'LeaveValidate', 'guard_name' => 'api', 'title' => 'Licencias (Validación)', 'description' => 'Validación de licencias laborales conforme a la normativa.']);
        Permission::create(['name' => 'LeaveValidate.All', 'guard_name' => 'api', 'title' => 'Listado de licencias para validación', 'description' => 'Permite visualizar licencias disponibles para validación.']);
        Permission::create(['name' => 'LeaveValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de licencia', 'description' => 'Permite consultar el detalle de una licencia.']);
        Permission::create(['name' => 'LeaveValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de licencia', 'description' => 'Permite aprobar licencias laborales.']);
        Permission::create(['name' => 'LeaveValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de licencia', 'description' => 'Permite rechazar licencias laborales.']);

        //================================================================================================================

        Role::create(['name' => 'CertificateValidate', 'guard_name' => 'api', 'title' => 'Certificados (Validación)', 'description' => 'Validación de certificados laborales.']);
        Permission::create(['name' => 'CertificateValidate.All', 'guard_name' => 'api', 'title' => 'Listado de certificados', 'description' => 'Permite visualizar certificados disponibles para validación.']);
        Permission::create(['name' => 'CertificateValidate.Find', 'guard_name' => 'api', 'title' => 'Consulta de certificado', 'description' => 'Permite consultar el detalle de un certificado.']);
        Permission::create(['name' => 'CertificateValidate.Approved', 'guard_name' => 'api', 'title' => 'Aprobación de certificado', 'description' => 'Permite aprobar certificados laborales.']);
        Permission::create(['name' => 'CertificateValidate.Rejected', 'guard_name' => 'api', 'title' => 'Rechazo de certificado', 'description' => 'Permite rechazar certificados laborales.']);

        //================================================================================================================

        Role::create(['name' => 'Enterprise', 'guard_name' => 'api', 'title' => 'Empresa', 'description' => 'Administración de empresas del sistema.']);
        Permission::create(['name' => 'Enterprise.All', 'guard_name' => 'api', 'title' => 'Listado de empresas', 'description' => 'Permite visualizar el listado de empresas registradas.']);
        Permission::create(['name' => 'Enterprise.Find', 'guard_name' => 'api', 'title' => 'Consulta de empresa', 'description' => 'Permite consultar el detalle de una empresa.']);
        Permission::create(['name' => 'Enterprise.Store', 'guard_name' => 'api', 'title' => 'Creación de empresa', 'description' => 'Permite registrar nuevas empresas.']);
        Permission::create(['name' => 'Enterprise.Update', 'guard_name' => 'api', 'title' => 'Actualización de empresa', 'description' => 'Permite modificar la información de una empresa.']);
        Permission::create(['name' => 'Enterprise.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de empresa', 'description' => 'Permite eliminar empresas.']);
        Permission::create(['name' => 'Enterprise.Restore', 'guard_name' => 'api', 'title' => 'Restauración de empresa', 'description' => 'Permite restaurar empresas eliminadas.']);
        Permission::create(['name' => 'Enterprise.Entity.All', 'guard_name' => 'api', 'title' => 'Listado de entidades', 'description' => 'Permite visualizar el listado de entidades de la empresa.']);
        Permission::create(['name' => 'Enterprise.Entity.Find', 'guard_name' => 'api', 'title' => 'Consulta de entidad', 'description' => 'Permite consultar el detalle de una entidad.']);
        Permission::create(['name' => 'Enterprise.Entity.Store', 'guard_name' => 'api', 'title' => 'Creación de entidad', 'description' => 'Permite registrar nuevas entidades.']);
        Permission::create(['name' => 'Enterprise.Entity.Update', 'guard_name' => 'api', 'title' => 'Actualización de entidad', 'description' => 'Permite modificar la información de una entidad.']);
        Permission::create(['name' => 'Enterprise.Entity.Profile', 'guard_name' => 'api', 'title' => 'Actualización de perfil de entidad', 'description' => 'Permite modificar la información del perfil de una entidad.']);
        Permission::create(['name' => 'Enterprise.Entity.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de entidad', 'description' => 'Permite eliminar entidades.']);
        Permission::create(['name' => 'Enterprise.Entity.Restore', 'guard_name' => 'api', 'title' => 'Restauración de entidad', 'description' => 'Permite restaurar entidades eliminadas.']);
        Permission::create(['name' => 'Enterprise.Entity.Gallery.All', 'guard_name' => 'api', 'title' => 'Listado de galerías', 'description' => 'Permite visualizar galerías de la entidad.']);
        Permission::create(['name' => 'Enterprise.Entity.Gallery.Find', 'guard_name' => 'api', 'title' => 'Consulta de galería', 'description' => 'Permite consultar el detalle de una galería.']);
        Permission::create(['name' => 'Enterprise.Entity.Gallery.Store', 'guard_name' => 'api', 'title' => 'Creación de galería', 'description' => 'Permite registrar nuevas galerías.']);
        Permission::create(['name' => 'Enterprise.Entity.Gallery.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de galería', 'description' => 'Permite eliminar galerías.']);
        Permission::create(['name' => 'Enterprise.Entity.Gallery.Destroy', 'guard_name' => 'api', 'title' => 'Eliminación permanente de galería', 'description' => 'Permite eliminar definitivamente una galería.']);
        Permission::create(['name' => 'Enterprise.Entity.Gallery.Restore', 'guard_name' => 'api', 'title' => 'Restauración de galería', 'description' => 'Permite restaurar galerías eliminadas.']);
        Permission::create(['name' => 'Enterprise.Entity.Template.All', 'guard_name' => 'api', 'title' => 'Listado de plantillas', 'description' => 'Permite visualizar plantillas de la entidad.']);
        Permission::create(['name' => 'Enterprise.Entity.Template.Find', 'guard_name' => 'api', 'title' => 'Consulta de plantilla', 'description' => 'Permite consultar el detalle de una plantilla.']);
        Permission::create(['name' => 'Enterprise.Entity.Template.Store', 'guard_name' => 'api', 'title' => 'Creación de plantilla', 'description' => 'Permite registrar nuevas plantillas.']);
        Permission::create(['name' => 'Enterprise.Entity.Template.Settings', 'guard_name' => 'api', 'title' => 'Configuración de plantilla', 'description' => 'Permite configurar parámetros de una plantilla.']);
        Permission::create(['name' => 'Enterprise.Entity.Template.Preview', 'guard_name' => 'api', 'title' => 'Vista previa de plantilla', 'description' => 'Permite visualizar una plantilla antes de su uso.']);
        Permission::create(['name' => 'Enterprise.Entity.Template.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de plantilla', 'description' => 'Permite eliminar plantillas.']);
        Permission::create(['name' => 'Enterprise.Entity.Template.Restore', 'guard_name' => 'api', 'title' => 'Restauración de plantilla', 'description' => 'Permite restaurar plantillas eliminadas.']);
        Permission::create(['name' => 'Enterprise.Entity.Headquarter.All', 'guard_name' => 'api', 'title' => 'Listado de sedes', 'description' => 'Permite visualizar sedes registradas.']);
        Permission::create(['name' => 'Enterprise.Entity.Headquarter.Find', 'guard_name' => 'api', 'title' => 'Consulta de sede', 'description' => 'Permite consultar el detalle de una sede.']);
        Permission::create(['name' => 'Enterprise.Entity.Headquarter.Store', 'guard_name' => 'api', 'title' => 'Creación de sede', 'description' => 'Permite registrar nuevas sedes.']);
        Permission::create(['name' => 'Enterprise.Entity.Headquarter.Update', 'guard_name' => 'api', 'title' => 'Actualización de sede', 'description' => 'Permite modificar información de sedes.']);
        Permission::create(['name' => 'Enterprise.Entity.Headquarter.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de sede', 'description' => 'Permite eliminar sedes.']);
        Permission::create(['name' => 'Enterprise.Entity.Headquarter.Restore', 'guard_name' => 'api', 'title' => 'Restauración de sede', 'description' => 'Permite restaurar sedes eliminadas.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.All', 'guard_name' => 'api', 'title' => 'Listado de aliados', 'description' => 'Permite visualizar los aliados registrados.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Find', 'guard_name' => 'api', 'title' => 'Consultar aliado', 'description' => 'Permite consultar la información de un aliado registrado.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Store', 'guard_name' => 'api', 'title' => 'Crear aliado', 'description' => 'Permite registrar nuevos aliados en el sistema.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Update', 'guard_name' => 'api', 'title' => 'Actualizar aliado', 'description' => 'Permite actualizar la información de los aliados registrados.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Delete', 'guard_name' => 'api', 'title' => 'Inactivar aliado', 'description' => 'Permite inactivar aliados registrados en el sistema.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Restore', 'guard_name' => 'api', 'title' => 'Activar aliado', 'description' => 'Permite activar nuevamente aliados previamente inactivados.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Campaign.All', 'guard_name' => 'api', 'title' => 'Listado de campañas', 'description' => 'Permite visualizar las campañas registradas.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Campaign.Find', 'guard_name' => 'api', 'title' => 'Consultar campaña', 'description' => 'Permite consultar la información de una campaña registrada.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Campaign.Store', 'guard_name' => 'api', 'title' => 'Crear campaña', 'description' => 'Permite registrar nuevas campañas en el sistema.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Campaign.Update', 'guard_name' => 'api', 'title' => 'Actualizar campaña', 'description' => 'Permite actualizar la información de las campañas registradas.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Campaign.Delete', 'guard_name' => 'api', 'title' => 'Inactivar campaña', 'description' => 'Permite inactivar campañas registradas en el sistema.']);
        Permission::create(['name' => 'Enterprise.Entity.Ally.Campaign.Restore', 'guard_name' => 'api', 'title' => 'Activar campaña', 'description' => 'Permite activar nuevamente campañas previamente inactivadas.']);
        //================================================================================================================

        Role::create(['name' => 'Area', 'guard_name' => 'api', 'title' => 'Área', 'description' => 'Administración de áreas organizacionales.']);
        Permission::create(['name' => 'Area.All', 'guard_name' => 'api', 'title' => 'Listado de áreas', 'description' => 'Permite visualizar el listado de áreas registradas.']);
        Permission::create(['name' => 'Area.Find', 'guard_name' => 'api', 'title' => 'Consulta de área', 'description' => 'Permite consultar el detalle de un área.']);
        Permission::create(['name' => 'Area.Store', 'guard_name' => 'api', 'title' => 'Creación de área', 'description' => 'Permite registrar nuevas áreas.']);
        Permission::create(['name' => 'Area.Update', 'guard_name' => 'api', 'title' => 'Actualización de área', 'description' => 'Permite modificar información de áreas.']);
        Permission::create(['name' => 'Area.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de área', 'description' => 'Permite eliminar áreas.']);
        Permission::create(['name' => 'Area.Restore', 'guard_name' => 'api', 'title' => 'Restauración de área', 'description' => 'Permite restaurar áreas eliminadas.']);

        //================================================================================================================

        Role::create(['name' => 'Skill', 'guard_name' => 'api', 'title' => 'Habilidad', 'description' => 'Gestión de habilidades y competencias.']);
        Permission::create(['name' => 'Skill.All', 'guard_name' => 'api', 'title' => 'Listado de habilidades', 'description' => 'Permite visualizar habilidades registradas.']);
        Permission::create(['name' => 'Skill.Find', 'guard_name' => 'api', 'title' => 'Consulta de habilidad', 'description' => 'Permite consultar el detalle de una habilidad.']);
        Permission::create(['name' => 'Skill.Store', 'guard_name' => 'api', 'title' => 'Creación de habilidad', 'description' => 'Permite registrar nuevas habilidades.']);
        Permission::create(['name' => 'Skill.Update', 'guard_name' => 'api', 'title' => 'Actualización de habilidad', 'description' => 'Permite modificar información de habilidades.']);
        Permission::create(['name' => 'Skill.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de habilidad', 'description' => 'Permite eliminar habilidades.']);
        Permission::create(['name' => 'Skill.Restore', 'guard_name' => 'api', 'title' => 'Restauración de habilidad', 'description' => 'Permite restaurar habilidades eliminadas.']);

        //================================================================================================================

        Role::create(['name' => 'Position', 'guard_name' => 'api', 'title' => 'Perfil de cargo', 'description' => 'Gestión de perfiles de cargo.']);
        Permission::create(['name' => 'Position.All', 'guard_name' => 'api', 'title' => 'Listado de perfiles de cargo', 'description' => 'Permite visualizar perfiles de cargo registrados.']);
        Permission::create(['name' => 'Position.Find', 'guard_name' => 'api', 'title' => 'Consulta de perfil de cargo', 'description' => 'Permite consultar el detalle de un perfil de cargo.']);
        Permission::create(['name' => 'Position.Store', 'guard_name' => 'api', 'title' => 'Creación de perfil de cargo', 'description' => 'Permite registrar nuevos perfiles de cargo.']);
        Permission::create(['name' => 'Position.Update', 'guard_name' => 'api', 'title' => 'Actualización de perfil de cargo', 'description' => 'Permite modificar información de perfiles de cargo.']);
        Permission::create(['name' => 'Position.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de perfil de cargo', 'description' => 'Permite eliminar perfiles de cargo.']);
        Permission::create(['name' => 'Position.Restore', 'guard_name' => 'api', 'title' => 'Restauración de perfil de cargo', 'description' => 'Permite restaurar perfiles de cargo eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'MaritalStatus', 'guard_name' => 'api', 'title' => 'Estado civil', 'description' => 'Administración de estados civiles.']);
        Permission::create(['name' => 'MaritalStatus.All', 'guard_name' => 'api', 'title' => 'Listado de estados civiles', 'description' => 'Permite visualizar estados civiles registrados.']);
        Permission::create(['name' => 'MaritalStatus.Find', 'guard_name' => 'api', 'title' => 'Consulta de estado civil', 'description' => 'Permite consultar el detalle de un estado civil.']);
        Permission::create(['name' => 'MaritalStatus.Store', 'guard_name' => 'api', 'title' => 'Creación de estado civil', 'description' => 'Permite registrar nuevos estados civiles.']);
        Permission::create(['name' => 'MaritalStatus.Update', 'guard_name' => 'api', 'title' => 'Actualización de estado civil', 'description' => 'Permite modificar información de estados civiles.']);
        Permission::create(['name' => 'MaritalStatus.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de estado civil', 'description' => 'Permite eliminar estados civiles.']);
        Permission::create(['name' => 'MaritalStatus.Restore', 'guard_name' => 'api', 'title' => 'Restauración de estado civil', 'description' => 'Permite restaurar estados civiles eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'WorkDay', 'guard_name' => 'api', 'title' => 'Jornada laboral', 'description' => 'Gestión de jornadas laborales.']);
        Permission::create(['name' => 'WorkDay.All', 'guard_name' => 'api', 'title' => 'Listado de jornadas laborales', 'description' => 'Permite visualizar jornadas laborales registradas.']);
        Permission::create(['name' => 'WorkDay.Find', 'guard_name' => 'api', 'title' => 'Consulta de jornada laboral', 'description' => 'Permite consultar el detalle de una jornada laboral.']);
        Permission::create(['name' => 'WorkDay.Store', 'guard_name' => 'api', 'title' => 'Creación de jornada laboral', 'description' => 'Permite registrar nuevas jornadas laborales.']);
        Permission::create(['name' => 'WorkDay.Update', 'guard_name' => 'api', 'title' => 'Actualización de jornada laboral', 'description' => 'Permite modificar información de jornadas laborales.']);
        Permission::create(['name' => 'WorkDay.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de jornada laboral', 'description' => 'Permite eliminar jornadas laborales.']);
        Permission::create(['name' => 'WorkDay.Restore', 'guard_name' => 'api', 'title' => 'Restauración de jornada laboral', 'description' => 'Permite restaurar jornadas laborales eliminadas.']);

        //================================================================================================================

        Role::create(['name' => 'PhoneValidation', 'guard_name' => 'api', 'title' => 'Validación de teléfono', 'description' => 'Validación y control de números telefónicos.']);
        Permission::create(['name' => 'PhoneValidation.All', 'guard_name' => 'api', 'title' => 'Listado de teléfonos', 'description' => 'Permite visualizar números telefónicos registrados.']);
        Permission::create(['name' => 'PhoneValidation.Find', 'guard_name' => 'api', 'title' => 'Consulta de teléfono', 'description' => 'Permite consultar el detalle de un número telefónico.']);
        Permission::create(['name' => 'PhoneValidation.Setting', 'guard_name' => 'api', 'title' => 'Configuración de validación telefónica', 'description' => 'Permite administrar configuraciones de validación de teléfonos.']);

        //================================================================================================================

        Role::create(['name' => 'Benefit', 'guard_name' => 'api', 'title' => 'Beneficios', 'description' => 'Gestión de beneficios laborales.']);
        Permission::create(['name' => 'Benefit.All', 'guard_name' => 'api', 'title' => 'Listado de beneficios', 'description' => 'Permite visualizar beneficios registrados.']);
        Permission::create(['name' => 'Benefit.Find', 'guard_name' => 'api', 'title' => 'Consulta de beneficio', 'description' => 'Permite consultar el detalle de un beneficio.']);
        Permission::create(['name' => 'Benefit.Store', 'guard_name' => 'api', 'title' => 'Creación de beneficio', 'description' => 'Permite registrar nuevos beneficios.']);
        Permission::create(['name' => 'Benefit.Update', 'guard_name' => 'api', 'title' => 'Actualización de beneficio', 'description' => 'Permite modificar información de beneficios.']);
        Permission::create(['name' => 'Benefit.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de beneficio', 'description' => 'Permite eliminar beneficios.']);
        Permission::create(['name' => 'Benefit.Restore', 'guard_name' => 'api', 'title' => 'Restauración de beneficio', 'description' => 'Permite restaurar beneficios eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'AcademicLevel', 'guard_name' => 'api', 'title' => 'Nivel académico', 'description' => 'Administración de niveles académicos.']);
        Permission::create(['name' => 'AcademicLevel.All', 'guard_name' => 'api', 'title' => 'Listado de niveles académicos', 'description' => 'Permite visualizar niveles académicos registrados.']);
        Permission::create(['name' => 'AcademicLevel.Find', 'guard_name' => 'api', 'title' => 'Consulta de nivel académico', 'description' => 'Permite consultar el detalle de un nivel académico.']);
        Permission::create(['name' => 'AcademicLevel.Store', 'guard_name' => 'api', 'title' => 'Creación de nivel académico', 'description' => 'Permite registrar nuevos niveles académicos.']);
        Permission::create(['name' => 'AcademicLevel.Update', 'guard_name' => 'api', 'title' => 'Actualización de nivel académico', 'description' => 'Permite modificar información de niveles académicos.']);
        Permission::create(['name' => 'AcademicLevel.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de nivel académico', 'description' => 'Permite eliminar niveles académicos.']);
        Permission::create(['name' => 'AcademicLevel.Restore', 'guard_name' => 'api', 'title' => 'Restauración de nivel académico', 'description' => 'Permite restaurar niveles académicos eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'ContractType', 'guard_name' => 'api', 'title' => 'Tipo de contrato', 'description' => 'Gestión de tipos de contrato.']);
        Permission::create(['name' => 'ContractType.All', 'guard_name' => 'api', 'title' => 'Listado de tipos de contrato', 'description' => 'Permite visualizar tipos de contrato registrados.']);
        Permission::create(['name' => 'ContractType.Find', 'guard_name' => 'api', 'title' => 'Consulta de tipo de contrato', 'description' => 'Permite consultar el detalle de un tipo de contrato.']);
        Permission::create(['name' => 'ContractType.Store', 'guard_name' => 'api', 'title' => 'Creación de tipo de contrato', 'description' => 'Permite registrar nuevos tipos de contrato.']);
        Permission::create(['name' => 'ContractType.Update', 'guard_name' => 'api', 'title' => 'Actualización de tipo de contrato', 'description' => 'Permite modificar información de tipos de contrato.']);
        Permission::create(['name' => 'ContractType.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de tipo de contrato', 'description' => 'Permite eliminar tipos de contrato.']);
        Permission::create(['name' => 'ContractType.Restore', 'guard_name' => 'api', 'title' => 'Restauración de tipo de contrato', 'description' => 'Permite restaurar tipos de contrato eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'WorkArrangement', 'guard_name' => 'api', 'title' => 'Modalidad de trabajo', 'description' => 'Gestión de modalidades de trabajo.']);
        Permission::create(['name' => 'WorkArrangement.All', 'guard_name' => 'api', 'title' => 'Listado de modalidades de trabajo', 'description' => 'Permite visualizar modalidades registradas.']);
        Permission::create(['name' => 'WorkArrangement.Find', 'guard_name' => 'api', 'title' => 'Consulta de modalidad', 'description' => 'Permite consultar el detalle de una modalidad de trabajo.']);
        Permission::create(['name' => 'WorkArrangement.Store', 'guard_name' => 'api', 'title' => 'Creación de modalidad', 'description' => 'Permite registrar nuevas modalidades de trabajo.']);
        Permission::create(['name' => 'WorkArrangement.Update', 'guard_name' => 'api', 'title' => 'Actualización de modalidad', 'description' => 'Permite modificar información de modalidades de trabajo.']);
        Permission::create(['name' => 'WorkArrangement.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de modalidad', 'description' => 'Permite eliminar modalidades de trabajo.']);
        Permission::create(['name' => 'WorkArrangement.Restore', 'guard_name' => 'api', 'title' => 'Restauración de modalidad', 'description' => 'Permite restaurar modalidades eliminadas.']);

        //================================================================================================================

        Role::create(['name' => 'RiskManager', 'guard_name' => 'api', 'title' => 'Administradora de riesgos laborales', 'description' => 'Gestión de administradoras de riesgos laborales.']);
        Permission::create(['name' => 'RiskManager.All', 'guard_name' => 'api', 'title' => 'Listado de ARL', 'description' => 'Permite visualizar administradoras de riesgos laborales registradas.']);
        Permission::create(['name' => 'RiskManager.Find', 'guard_name' => 'api', 'title' => 'Consulta de ARL', 'description' => 'Permite consultar el detalle de una administradora de riesgos laborales.']);
        Permission::create(['name' => 'RiskManager.Store', 'guard_name' => 'api', 'title' => 'Creación de ARL', 'description' => 'Permite registrar nuevas administradoras de riesgos laborales.']);
        Permission::create(['name' => 'RiskManager.Update', 'guard_name' => 'api', 'title' => 'Actualización de ARL', 'description' => 'Permite modificar información de administradoras de riesgos laborales.']);
        Permission::create(['name' => 'RiskManager.Toggle', 'guard_name' => 'api', 'title' => 'Activación o desactivación de ARL', 'description' => 'Permite activar o desactivar administradoras de riesgos laborales.']);
        Permission::create(['name' => 'RiskManager.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de ARL', 'description' => 'Permite eliminar administradoras de riesgos laborales.']);
        Permission::create(['name' => 'RiskManager.Restore', 'guard_name' => 'api', 'title' => 'Restauración de ARL', 'description' => 'Permite restaurar administradoras eliminadas.']);

        //================================================================================================================

        Role::create(['name' => 'HealthEntity', 'guard_name' => 'api', 'title' => 'Entidades de salud', 'description' => 'Gestión de entidades de salud (EPS).']);
        Permission::create(['name' => 'HealthEntity.All', 'guard_name' => 'api', 'title' => 'Listado de entidades de salud', 'description' => 'Permite visualizar entidades de salud registradas.']);
        Permission::create(['name' => 'HealthEntity.Find', 'guard_name' => 'api', 'title' => 'Consulta de entidad de salud', 'description' => 'Permite consultar el detalle de una entidad de salud.']);
        Permission::create(['name' => 'HealthEntity.Store', 'guard_name' => 'api', 'title' => 'Creación de entidad de salud', 'description' => 'Permite registrar nuevas entidades de salud.']);
        Permission::create(['name' => 'HealthEntity.Update', 'guard_name' => 'api', 'title' => 'Actualización de entidad de salud', 'description' => 'Permite modificar información de entidades de salud.']);
        Permission::create(['name' => 'HealthEntity.Toggle', 'guard_name' => 'api', 'title' => 'Activación o desactivación de entidad de salud', 'description' => 'Permite activar o desactivar entidades de salud.']);
        Permission::create(['name' => 'HealthEntity.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de entidad de salud', 'description' => 'Permite eliminar entidades de salud.']);
        Permission::create(['name' => 'HealthEntity.Restore', 'guard_name' => 'api', 'title' => 'Restauración de entidad de salud', 'description' => 'Permite restaurar entidades eliminadas.']);

        //================================================================================================================

        Role::create(['name' => 'CompensationFund', 'guard_name' => 'api', 'title' => 'Caja de compensación', 'description' => 'Gestión de cajas de compensación familiar.']);
        Permission::create(['name' => 'CompensationFund.All', 'guard_name' => 'api', 'title' => 'Listado de cajas de compensación', 'description' => 'Permite visualizar cajas de compensación registradas.']);
        Permission::create(['name' => 'CompensationFund.Find', 'guard_name' => 'api', 'title' => 'Consulta de caja de compensación', 'description' => 'Permite consultar el detalle de una caja de compensación.']);
        Permission::create(['name' => 'CompensationFund.Store', 'guard_name' => 'api', 'title' => 'Creación de caja de compensación', 'description' => 'Permite registrar nuevas cajas de compensación.']);
        Permission::create(['name' => 'CompensationFund.Update', 'guard_name' => 'api', 'title' => 'Actualización de caja de compensación', 'description' => 'Permite modificar información de cajas de compensación.']);
        Permission::create(['name' => 'CompensationFund.Toggle', 'guard_name' => 'api', 'title' => 'Activación o desactivación de caja de compensación', 'description' => 'Permite activar o desactivar cajas de compensación.']);
        Permission::create(['name' => 'CompensationFund.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de caja de compensación', 'description' => 'Permite eliminar cajas de compensación.']);
        Permission::create(['name' => 'CompensationFund.Restore', 'guard_name' => 'api', 'title' => 'Restauración de caja de compensación', 'description' => 'Permite restaurar cajas eliminadas.']);

        //================================================================================================================

        Role::create(['name' => 'Bank', 'guard_name' => 'api', 'title' => 'Bancos', 'description' => 'Gestión de entidades bancarias.']);
        Permission::create(['name' => 'Bank.All', 'guard_name' => 'api', 'title' => 'Listado de bancos', 'description' => 'Permite visualizar bancos registrados.']);
        Permission::create(['name' => 'Bank.Find', 'guard_name' => 'api', 'title' => 'Consulta de banco', 'description' => 'Permite consultar el detalle de un banco.']);
        Permission::create(['name' => 'Bank.Store', 'guard_name' => 'api', 'title' => 'Creación de banco', 'description' => 'Permite registrar nuevos bancos.']);
        Permission::create(['name' => 'Bank.Update', 'guard_name' => 'api', 'title' => 'Actualización de banco', 'description' => 'Permite modificar información de bancos.']);
        Permission::create(['name' => 'Bank.Toggle', 'guard_name' => 'api', 'title' => 'Activación o desactivación de banco', 'description' => 'Permite activar o desactivar bancos.']);
        Permission::create(['name' => 'Bank.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de banco', 'description' => 'Permite eliminar bancos.']);
        Permission::create(['name' => 'Bank.Restore', 'guard_name' => 'api', 'title' => 'Restauración de banco', 'description' => 'Permite restaurar bancos eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'PensionFund', 'guard_name' => 'api', 'title' => 'Fondo de pensiones', 'description' => 'Gestión de fondos de pensiones.']);
        Permission::create(['name' => 'PensionFund.All', 'guard_name' => 'api', 'title' => 'Listado de fondos de pensiones', 'description' => 'Permite visualizar fondos registrados.']);
        Permission::create(['name' => 'PensionFund.Find', 'guard_name' => 'api', 'title' => 'Consulta de fondo de pensión', 'description' => 'Permite consultar el detalle de un fondo de pensión.']);
        Permission::create(['name' => 'PensionFund.Store', 'guard_name' => 'api', 'title' => 'Creación de fondo de pensión', 'description' => 'Permite registrar nuevos fondos de pensiones.']);
        Permission::create(['name' => 'PensionFund.Update', 'guard_name' => 'api', 'title' => 'Actualización de fondo de pensión', 'description' => 'Permite modificar información de fondos de pensiones.']);
        Permission::create(['name' => 'PensionFund.Toggle', 'guard_name' => 'api', 'title' => 'Activación o desactivación de fondo de pensión', 'description' => 'Permite activar o desactivar fondos de pensiones.']);
        Permission::create(['name' => 'PensionFund.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de fondo de pensión', 'description' => 'Permite eliminar fondos de pensiones.']);
        Permission::create(['name' => 'PensionFund.Restore', 'guard_name' => 'api', 'title' => 'Restauración de fondo de pensión', 'description' => 'Permite restaurar fondos eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'DocumentType', 'guard_name' => 'api', 'title' => 'Documento de identidad', 'description' => 'Gestión de tipos de documentos de identidad.']);
        Permission::create(['name' => 'DocumentType.All', 'guard_name' => 'api', 'title' => 'Listado de tipos de documento', 'description' => 'Permite visualizar tipos de documentos registrados.']);
        Permission::create(['name' => 'DocumentType.Find', 'guard_name' => 'api', 'title' => 'Consulta de tipo de documento', 'description' => 'Permite consultar el detalle de un tipo de documento.']);
        Permission::create(['name' => 'DocumentType.Store', 'guard_name' => 'api', 'title' => 'Creación de tipo de documento', 'description' => 'Permite registrar nuevos tipos de documento.']);
        Permission::create(['name' => 'DocumentType.Update', 'guard_name' => 'api', 'title' => 'Actualización de tipo de documento', 'description' => 'Permite modificar información de tipos de documento.']);
        Permission::create(['name' => 'DocumentType.Toggle', 'guard_name' => 'api', 'title' => 'Activación o desactivación de tipo de documento', 'description' => 'Permite activar o desactivar tipos de documento.']);
        Permission::create(['name' => 'DocumentType.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de tipo de documento', 'description' => 'Permite eliminar tipos de documento.']);
        Permission::create(['name' => 'DocumentType.Restore', 'guard_name' => 'api', 'title' => 'Restauración de tipo de documento', 'description' => 'Permite restaurar tipos eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'FiscalDocument', 'guard_name' => 'api', 'title' => 'Documento fiscal', 'description' => 'Gestión de documentos fiscales.']);
        Permission::create(['name' => 'FiscalDocument.All', 'guard_name' => 'api', 'title' => 'Listado de documentos fiscales', 'description' => 'Permite visualizar documentos fiscales registrados.']);
        Permission::create(['name' => 'FiscalDocument.Find', 'guard_name' => 'api', 'title' => 'Consulta de documento fiscal', 'description' => 'Permite consultar el detalle de un documento fiscal.']);
        Permission::create(['name' => 'FiscalDocument.Store', 'guard_name' => 'api', 'title' => 'Creación de documento fiscal', 'description' => 'Permite registrar nuevos documentos fiscales.']);
        Permission::create(['name' => 'FiscalDocument.Update', 'guard_name' => 'api', 'title' => 'Actualización de documento fiscal', 'description' => 'Permite modificar información de documentos fiscales.']);
        Permission::create(['name' => 'FiscalDocument.Toggle', 'guard_name' => 'api', 'title' => 'Activación o desactivación de documento fiscal', 'description' => 'Permite activar o desactivar documentos fiscales.']);
        Permission::create(['name' => 'FiscalDocument.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de documento fiscal', 'description' => 'Permite eliminar documentos fiscales.']);
        Permission::create(['name' => 'FiscalDocument.Restore', 'guard_name' => 'api', 'title' => 'Restauración de documento fiscal', 'description' => 'Permite restaurar documentos eliminados.']);

        //================================================================================================================

        Role::create(['name' => 'EntityType', 'guard_name' => 'api', 'title' => 'Tipo de razón social', 'description' => 'Gestión de tipos de razón social.']);
        Permission::create(['name' => 'EntityType.All', 'guard_name' => 'api', 'title' => 'Listado de tipos de razón social', 'description' => 'Permite visualizar tipos registrados.']);
        Permission::create(['name' => 'EntityType.Find', 'guard_name' => 'api', 'title' => 'Consulta de tipo de razón social', 'description' => 'Permite consultar el detalle de un tipo de razón social.']);
        Permission::create(['name' => 'EntityType.Store', 'guard_name' => 'api', 'title' => 'Creación de tipo de razón social', 'description' => 'Permite registrar nuevos tipos.']);
        Permission::create(['name' => 'EntityType.Update', 'guard_name' => 'api', 'title' => 'Actualización de tipo de razón social', 'description' => 'Permite modificar información de tipos.']);
        Permission::create(['name' => 'EntityType.Toggle', 'guard_name' => 'api', 'title' => 'Activación o desactivación de tipo', 'description' => 'Permite activar o desactivar tipos de razón social.']);
        Permission::create(['name' => 'EntityType.Delete', 'guard_name' => 'api', 'title' => 'Eliminación de tipo', 'description' => 'Permite eliminar tipos de razón social.']);
        Permission::create(['name' => 'EntityType.Restore', 'guard_name' => 'api', 'title' => 'Restauración de tipo', 'description' => 'Permite restaurar tipos eliminados.']);

        Role::create(['name' => 'EpidemiologicalProgram', 'guard_name' => 'api', 'title' => 'Programa de vigilancia epidemiológica', 'description' => 'Gestión de programas de vigilancia epidemiológica.']);

        Role::create(['name' => 'PolicyType', 'guard_name' => 'api', 'title' => 'Tipo de póliza', 'description' => 'Gestión de tipos de póliza.']);

        Role::create(['name' => 'AddendumType', 'guard_name' => 'api', 'title' => 'Tipo de otrosí', 'description' => 'Gestión de tipos de otrosí contractuales.']);

        Role::create(['name' => 'Person', 'guard_name' => 'api', 'title' => 'Personas', 'description' => 'Gestión de información de personas.']);

        Role::create(['name' => 'Role', 'guard_name' => 'api', 'title' => 'Roles', 'description' => 'Gestión de roles del sistema.']);

        Role::create(['name' => 'Permission', 'guard_name' => 'api', 'title' => 'Permisos', 'description' => 'Gestión de permisos del sistema.']);

        Role::create(['name' => 'Employee', 'guard_name' => 'api', 'title' => 'Empleados', 'description' => 'Gestión de información de empleados.']);

        Role::create(['name' => 'Navigation', 'guard_name' => 'api', 'title' => 'Navegación', 'description' => 'Gestión de menús y estructura de navegación.']);

        Role::create(['name' => 'NotificationType', 'guard_name' => 'api', 'title' => 'Tipo de notificación', 'description' => 'Gestión de tipos de notificación.']);

        Role::create(['name' => 'ServiceProvider', 'guard_name' => 'api', 'title' => 'Proveedor de servicios', 'description' => 'Gestión de proveedores de servicios.']);

        Role::create(['name' => 'Form', 'guard_name' => 'api', 'title' => 'Formularios', 'description' => 'Gestión de formularios del sistema.']);

        //================================================================================================================
        // TIPOS DE AUSENCIA
        //================================================================================================================

        Role::create(['name' => 'AbsenceTypes', 'guard_name' => 'api', 'title' => 'Tipos de ausencia', 'description' => 'Administración de los tipos de ausencia disponibles en el sistema.']);
        Permission::create(['name' => 'AbsenceTypes.All', 'guard_name' => 'api', 'title' => 'Listado de tipos de ausencia', 'description' => 'Permite visualizar el listado de tipos de ausencia registrados.']);
        Permission::create(['name' => 'AbsenceTypes.Find', 'guard_name' => 'api', 'title' => 'Consulta de tipo de ausencia', 'description' => 'Permite consultar la información detallada de un tipo de ausencia.']);
        Permission::create(['name' => 'AbsenceTypes.Store', 'guard_name' => 'api', 'title' => 'Creación de tipo de ausencia', 'description' => 'Permite registrar nuevos tipos de ausencia en el sistema.']);
        Permission::create(['name' => 'AbsenceTypes.Update', 'guard_name' => 'api', 'title' => 'Actualización de tipo de ausencia', 'description' => 'Permite modificar la información de los tipos de ausencia existentes.']);
        Permission::create(['name' => 'AbsenceTypes.Setting', 'guard_name' => 'api', 'title' => 'Configuración de tipos de ausencia', 'description' => 'Permite acceder a la configuración y administración de los tipos de ausencia.']);

        //================================================================================================================
        // TIPOS DE VACACIONES
        //================================================================================================================

        Role::create(['name' => 'VacationTypes', 'guard_name' => 'api', 'title' => 'Tipos de vacaciones', 'description' => 'Administración de los tipos de vacaciones disponibles en el sistema.']);
        Permission::create(['name' => 'VacationTypes.All', 'guard_name' => 'api', 'title' => 'Listado de tipos de vacaciones', 'description' => 'Permite visualizar el listado de tipos de vacaciones registrados.']);
        Permission::create(['name' => 'VacationTypes.Find', 'guard_name' => 'api', 'title' => 'Consulta de tipo de vacaciones', 'description' => 'Permite consultar la información detallada de un tipo de vacaciones.']);
        Permission::create(['name' => 'VacationTypes.Store', 'guard_name' => 'api', 'title' => 'Creación de tipo de vacaciones', 'description' => 'Permite registrar nuevos tipos de vacaciones en el sistema.']);
        Permission::create(['name' => 'VacationTypes.Update', 'guard_name' => 'api', 'title' => 'Actualización de tipo de vacaciones', 'description' => 'Permite modificar la información de los tipos de vacaciones existentes.']);
        Permission::create(['name' => 'VacationTypes.Setting', 'guard_name' => 'api', 'title' => 'Configuración de tipos de vacaciones', 'description' => 'Permite acceder a la configuración y administración de los tipos de vacaciones.']);

        //================================================================================================================
        // TIPOS DE CESANTÍAS
        //================================================================================================================

        Role::create(['name' => 'SeveranceTypes', 'guard_name' => 'api', 'title' => 'Tipos de cesantías', 'description' => 'Administración de los tipos de cesantías disponibles en el sistema.']);
        Permission::create(['name' => 'SeveranceTypes.All', 'guard_name' => 'api', 'title' => 'Listado de tipos de cesantías', 'description' => 'Permite visualizar el listado de tipos de cesantías registrados.']);
        Permission::create(['name' => 'SeveranceTypes.Find', 'guard_name' => 'api', 'title' => 'Consulta de tipo de cesantía', 'description' => 'Permite consultar la información detallada de un tipo de cesantía.']);
        Permission::create(['name' => 'SeveranceTypes.Store', 'guard_name' => 'api', 'title' => 'Creación de tipo de cesantía', 'description' => 'Permite registrar nuevos tipos de cesantías en el sistema.']);
        Permission::create(['name' => 'SeveranceTypes.Update', 'guard_name' => 'api', 'title' => 'Actualización de tipo de cesantía', 'description' => 'Permite modificar la información de los tipos de cesantías existentes.']);
        Permission::create(['name' => 'SeveranceTypes.Setting', 'guard_name' => 'api', 'title' => 'Configuración de tipos de cesantías', 'description' => 'Permite acceder a la configuración y administración de los tipos de cesantías.']);

        //================================================================================================================
        // TIPOS DE INCAPACIDAD
        //================================================================================================================

        Role::create(['name' => 'DisabilityTypes', 'guard_name' => 'api', 'title' => 'Tipos de incapacidad', 'description' => 'Administración de los tipos de incapacidad disponibles en el sistema.']);
        Permission::create(['name' => 'DisabilityTypes.All', 'guard_name' => 'api', 'title' => 'Listado de tipos de incapacidad', 'description' => 'Permite visualizar el listado de tipos de incapacidad registrados.']);
        Permission::create(['name' => 'DisabilityTypes.Find', 'guard_name' => 'api', 'title' => 'Consulta de tipo de incapacidad', 'description' => 'Permite consultar la información detallada de un tipo de incapacidad.']);
        Permission::create(['name' => 'DisabilityTypes.Store', 'guard_name' => 'api', 'title' => 'Creación de tipo de incapacidad', 'description' => 'Permite registrar nuevos tipos de incapacidad en el sistema.']);
        Permission::create(['name' => 'DisabilityTypes.Update', 'guard_name' => 'api', 'title' => 'Actualización de tipo de incapacidad', 'description' => 'Permite modificar la información de los tipos de incapacidad existentes.']);
        Permission::create(['name' => 'DisabilityTypes.Setting', 'guard_name' => 'api', 'title' => 'Configuración de tipos de incapacidad', 'description' => 'Permite acceder a la configuración y administración de los tipos de incapacidad.']);

        //================================================================================================================
        // TIPOS DE LICENCIA
        //================================================================================================================

        Role::create(['name' => 'LicenseTypes', 'guard_name' => 'api', 'title' => 'Tipos de licencia', 'description' => 'Administración de los tipos de licencia disponibles en el sistema.']);
        Permission::create(['name' => 'LicenseTypes.All', 'guard_name' => 'api', 'title' => 'Listado de tipos de licencia', 'description' => 'Permite visualizar el listado de tipos de licencia registrados.']);
        Permission::create(['name' => 'LicenseTypes.Find', 'guard_name' => 'api', 'title' => 'Consulta de tipo de licencia', 'description' => 'Permite consultar la información detallada de un tipo de licencia.']);
        Permission::create(['name' => 'LicenseTypes.Store', 'guard_name' => 'api', 'title' => 'Creación de tipo de licencia', 'description' => 'Permite registrar nuevos tipos de licencia en el sistema.']);
        Permission::create(['name' => 'LicenseTypes.Update', 'guard_name' => 'api', 'title' => 'Actualización de tipo de licencia', 'description' => 'Permite modificar la información de los tipos de licencia existentes.']);
        Permission::create(['name' => 'LicenseTypes.Setting', 'guard_name' => 'api', 'title' => 'Configuración de tipos de licencia', 'description' => 'Permite acceder a la configuración y administración de los tipos de licencia.']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
