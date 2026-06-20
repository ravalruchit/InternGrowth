<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class MultiDomainCareerSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            'Software Development' => [
                'skills' => [
                    'HTML', 'CSS', 'JavaScript', 'React', 'Vue', 'Angular', 'PHP', 'Laravel', 'Node.js', 
                    'Express', 'MySQL', 'MongoDB', 'PostgreSQL', 'Flutter', 'React Native', 'Swift', 
                    'Kotlin', 'Git', 'Docker', 'AWS', 'Python', 'Django', 'Flask', 'Ruby on Rails', 
                    'Java', 'Spring Boot', 'C#', '.NET', 'Go', 'Rust', 'TypeScript', 'Tailwind CSS', 
                    'Bootstrap', 'Webpack', 'GraphQL', 'REST API', 'Jest', 'Selenium', 'Kubernetes', 'CI/CD'
                ]
            ],
            'UI/UX Design' => [
                'skills' => [
                    'Figma', 'Adobe XD', 'Photoshop', 'Illustrator', 'InDesign', 'Premiere Pro', 'After Effects', 
                    'Sketch', 'Wireframing', 'Prototyping', 'Design Systems', 'Typography', 'Color Theory', 
                    'User Research', 'Usability Testing', 'Information Architecture', 'Storyboarding', 
                    'Persona Creation', 'Vector Illustration', 'Branding', 'Logo Design', 'Layout Design', 
                    'Responsive Design', '3D Modeling', 'Blender', 'Framer', 'Webflow', 'CSS Grid', 
                    'SVG Animation', 'Accessibility (a11y)', 'User Flows', 'High-fidelity Mockups', 
                    'Low-fidelity Sketches', 'Moodboards', 'Design Thinking', 'Mobile App UI', 'Web Design', 
                    'Iconography', 'Visual Design'
                ]
            ],
            'Digital Marketing' => [
                'skills' => [
                    'SEO', 'SEM', 'Google Ads', 'Meta Ads', 'Google Analytics', 'Content Strategy', 
                    'Marketing Automation', 'Copywriting', 'Email Marketing', 'Mailchimp', 'A/B Testing', 
                    'Keyword Research', 'Link Building', 'Google Search Console', 'HubSpot', 'Social Media Ads', 
                    'Influencer Marketing', 'Content Calendar', 'Branding', 'Conversion Rate Optimization (CRO)', 
                    'Canva', 'TikTok Ads', 'Affiliate Marketing', 'Buffer', 'Hootsuite', 'Video Marketing', 
                    'Google Tag Manager', 'Lead Generation', 'Customer Retention', 'Market Analysis', 
                    'Competitor Analysis', 'Press Release', 'Copy Editing', 'Audience Segmentation', 
                    'Campaign Management', 'Viral Marketing', 'CRM', 'Salesforce', 'Public Relations (PR)', 'Local SEO'
                ]
            ],
            'Data & AI' => [
                'skills' => [
                    'Python', 'SQL', 'Power BI', 'Tableau', 'Pandas', 'NumPy', 'SciPy', 'Scikit-Learn', 
                    'TensorFlow', 'PyTorch', 'Keras', 'Machine Learning', 'Deep Learning', 'Prompt Engineering', 
                    'R Programming', 'Data Visualization', 'Data Cleaning', 'ETL', 'Big Data', 'Hadoop', 
                    'Spark', 'Jupyter Notebook', 'Feature Engineering', 'Natural Language Processing (NLP)', 
                    'Computer Vision', 'Reinforcement Learning', 'Neural Networks', 'Data Mining', 
                    'Predictive Modeling', 'Statistical Analysis', 'Git', 'LLMs', 'GPT API', 'Hugging Face', 
                    'Vector Databases', 'Pinecone', 'FAISS', 'LangChain', 'MLOps', 'Data Lakes'
                ]
            ],
            'Content & Business' => [
                'skills' => [
                    'Writing', 'Research', 'Documentation', 'Business Analysis', 'Excel', 'Google Sheets', 
                    'PowerPoint', 'Communication', 'Presentation Skills', 'Technical Writing', 'Blogging', 
                    'Creative Writing', 'SEO Writing', 'Proofreading', 'Business Development', 'Financial Modeling', 
                    'Market Research', 'SWOT Analysis', 'Agile Methodology', 'Scrum', 'Project Management', 
                    'Trello', 'Jira', 'Asana', 'Client Relations', 'Customer Support', 'Data Entry', 
                    'Operations Management', 'Strategy Formulation', 'Business Writing', 'Negotiation', 
                    'Public Speaking', 'Problem Solving', 'Time Management', 'CRM Tools', 'E-commerce Operations', 
                    'Proposal Writing', 'Pitch Decks', 'Event Planning', 'Supply Chain Basics'
                ]
            ]
        ];

        foreach ($domains as $domainName => $data) {
            foreach ($data['skills'] as $skillName) {
                Skill::updateOrCreate(
                    ['name' => $skillName],
                    ['domain' => $domainName]
                );
            }
        }
    }
}
